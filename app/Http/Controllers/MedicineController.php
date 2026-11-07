<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicineController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $medicines = Medicine::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('medicines.index', [
            'medicines' => $medicines,
            'search' => $search,
            'medicineCount' => Medicine::count(),
            'lowStockCount' => Medicine::where('stock', '<=', 10)->count(),
        ]);
    }

    public function create(): View
    {
        return view('medicines.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Medicine::create($this->validatedData($request));

        return redirect()->route('medicines.index')->with('success', 'Data obat berhasil ditambahkan.');
    }

    public function edit(Medicine $medicine): View
    {
        return view('medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine): RedirectResponse
    {
        $medicine->update($this->validatedData($request, $medicine));

        return redirect()->route('medicines.index')->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy(Medicine $medicine): RedirectResponse
    {
        if ($medicine->saleItems()->exists()) {
            return back()->with('error', 'Obat yang sudah tercatat dalam penjualan tidak dapat dihapus.');
        }

        $medicine->delete();

        return redirect()->route('medicines.index')->with('success', 'Data obat berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Medicine $medicine = null): array
    {
        return $request->validate([
            'sku' => ['required', 'string', 'max:40', 'unique:medicines,sku,'.$medicine?->id],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:40'],
            'purchase_price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'selling_price' => ['required', 'numeric', 'gt:0', 'max:9999999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'expiration_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);
    }
}
