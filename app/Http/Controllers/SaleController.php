<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(): View
    {
        return view('sales.index', [
            'sales' => Sale::with('items.medicine')->latest('sold_at')->paginate(12),
        ]);
    }

    public function create(): View
    {
        $medicines = Medicine::query()
            ->where('stock', '>', 0)
            ->where(function ($query) {
                $query->whereNull('expiration_date')->orWhereDate('expiration_date', '>=', today());
            })
            ->orderBy('name')
            ->get();
        $medicineOptions = $medicines->map(fn (Medicine $medicine) => [
            'id' => $medicine->id,
            'name' => $medicine->name,
            'price' => $medicine->selling_price,
            'stock' => $medicine->stock,
        ])->values();

        return view('sales.create', compact('medicines', 'medicineOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'in:tunai,transfer,qris'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicine_id' => ['required', 'integer', 'exists:medicines,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:4294967295'],
        ]);

        $sale = DB::transaction(function () use ($data) {
            $quantities = collect($data['items'])
                ->groupBy('medicine_id')
                ->map(fn ($items) => $items->sum('quantity'));

            $lockedMedicines = Medicine::query()
                ->whereIn('id', $quantities->keys())
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;
            $maximumTotalInCents = 99999999999999;
            foreach ($quantities as $medicineId => $quantity) {
                $medicine = $lockedMedicines->get($medicineId);

                if (! $medicine || $medicine->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => 'Stok obat tidak mencukupi untuk menyelesaikan penjualan.',
                    ]);
                }

                if ($medicine->expiration_date?->isBefore(today())) {
                    throw ValidationException::withMessages([
                        'items' => "Obat {$medicine->name} sudah melewati tanggal kedaluwarsa.",
                    ]);
                }

                $unitPrice = (int) round((float) $medicine->selling_price * 100);
                if ($unitPrice > intdiv($maximumTotalInCents - $total, $quantity)) {
                    throw ValidationException::withMessages([
                        'items' => 'Total transaksi melebihi batas nilai yang dapat disimpan.',
                    ]);
                }

                $total += $unitPrice * $quantity;
            }

            $sale = Sale::create([
                'invoice_number' => 'APT-'.now()->format('YmdHis').'-'.Str::upper(Str::random(5)),
                'customer_name' => $data['customer_name'] ?? null,
                'payment_method' => $data['payment_method'],
                'total' => $total / 100,
                'sold_at' => now(),
            ]);

            foreach ($quantities as $medicineId => $quantity) {
                $medicine = $lockedMedicines->get($medicineId);
                $unitPrice = (int) round((float) $medicine->selling_price * 100);

                $sale->items()->create([
                    'medicine_id' => $medicine->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice / 100,
                    'subtotal' => ($unitPrice * $quantity) / 100,
                ]);

                $medicine->decrement('stock', $quantity);
            }

            return $sale;
        });

        return redirect()->route('sales.show', $sale)->with('success', 'Penjualan berhasil disimpan dan stok telah diperbarui.');
    }

    public function show(Sale $sale): View
    {
        $sale->load('items.medicine');

        return view('sales.show', compact('sale'));
    }
}
