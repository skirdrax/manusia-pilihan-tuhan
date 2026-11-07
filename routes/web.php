<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RoleSwitchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GisController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

// Guest Routes (Login & Register Bersih untuk User)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes (Dashboard, Switch Role, Operasional Herbal & GIS)
Route::middleware('auth')->group(function () {
    // Dashboard Utama (otomatis menyesuaikan role user, kurir, admin)
    Route::get('/', DashboardController::class)->name('dashboard');

    // Tombol Cepat Pindah Peran di Kanan Atas
    Route::get('/switch-role/{role}', [RoleSwitchController::class, 'switch'])->name('switch.role');

    // Manajemen Obat Herbal (CRUD)
    Route::resource('medicines', MedicineController::class)->except('show');

    // Penjualan & Transaksi Herbal (Kasir)
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');

    // Manajemen Pengiriman Paket Herbal (CRUD Lengkap Tambah, Edit, Hapus)
    Route::resource('paket', PaketController::class)->except('show');

    // Peta GIS Distribusi & Pengiriman Herbal (Leaflet dengan Titik Indramayu & Jarak)
    Route::get('/gis', [GisController::class, 'index'])->name('gis.index');
    Route::get('/api/gis/points', [GisController::class, 'points'])->name('api.gis.points');
    Route::get('/api/gis/regions', [GisController::class, 'regions'])->name('api.gis.regions');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
