<?php

namespace App\Http\Controllers;

use App\Models\Kurir;
use App\Models\Medicine;
use App\Models\Paket;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const LOW_STOCK = 10;
    private const EXPIRING_DAYS = 60;

    public function __invoke(): View
    {
        $user = Auth::user();

        // 1. Tampilan Khusus Role 'user' (Pelanggan)
        if ($user && $user->role === 'user') {
            $medicines = Medicine::where('stock', '>', 0)->latest()->get();
            return view('dashboard-user', compact('medicines'));
        }

        // 2. Tampilan Khusus Role 'kurir' (Driver Pengantar)
        if ($user && $user->role === 'kurir') {
            // Cocokkan kurir berdasarkan nama atau ambil paket yang ditugaskan
            $kurir = Kurir::where('nama', 'like', '%' . $user->name . '%')->first();
            $myPakets = Paket::when($kurir, fn($q) => $q->where('kurir_id', $kurir->id))
                ->latest()
                ->get();

            return view('dashboard-kurir', compact('myPakets'));
        }

        // 3. Tampilan Utama Role 'admin' (Pengelola Sistem Lengkap)
        $soon = today()->addDays(self::EXPIRING_DAYS);

        // Statistik Produk Obat Herbal dari DB
        $stats = Medicine::selectRaw(
            'COUNT(*) as total,
             COALESCE(SUM(stock), 0) as units,
             COALESCE(SUM(stock * purchase_price), 0) as stock_value,
             COALESCE(SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END), 0) as out_count,
             COALESCE(SUM(CASE WHEN stock BETWEEN 1 AND '.self::LOW_STOCK.' THEN 1 ELSE 0 END), 0) as low_count'
        )->first();

        $total = (int) $stats->total;
        $out = (int) $stats->out_count;
        $low = (int) $stats->low_count;

        $attention = Medicine::where(function ($query) use ($soon) {
            $query->where('stock', '<=', self::LOW_STOCK)
                ->orWhere(fn ($q) => $q->whereNotNull('expiration_date')->whereDate('expiration_date', '<=', $soon));
        })->count();

        $categories = Medicine::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')->orderByDesc('total')->limit(6)->get();

        // Statistik Pengiriman & Logistik Herbal dari DB
        $paketStats = [
            'total'    => Paket::count(),
            'pending'  => Paket::where('status', 'pending')->count(),
            'dikirim'  => Paket::where('status', 'dikirim')->count(),
            'terkirim' => Paket::where('status', 'terkirim')->count(),
            'kurir'    => Kurir::count(),
        ];

        $latestPaket = Paket::with('kurir')->latest()->take(5)->get();

        return view('dashboard', [
            'total' => $total,
            'units' => (int) $stats->units,
            'stockValue' => (float) $stats->stock_value,
            'attention' => $attention,
            'statusCounts' => ['out' => $out, 'low' => $low, 'ok' => $total - $out - $low],
            'categories' => $categories,
            'maxCategory' => (int) ($categories->max('total') ?? 0),
            'restock' => Medicine::where('stock', '<=', self::LOW_STOCK)->orderBy('stock')->orderBy('name')->limit(6)->get(),
            'expiring' => Medicine::whereNotNull('expiration_date')->whereDate('expiration_date', '<=', $soon)
                ->orderBy('expiration_date')->limit(6)->get(),
            'latest' => Medicine::latest('id')->limit(5)->get(),
            'lowStockAt' => self::LOW_STOCK,
            'expiringDays' => self::EXPIRING_DAYS,
            'paketStats' => $paketStats,
            'latestPaket' => $latestPaket,
        ]);
    }
}
