<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GisController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

// Guest Routes (Login Pelanggan & Register Pelanggan)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Portal Login Khusus Petugas (Admin & Kurir) - Bisa diakses saat butuh masuk petugas
Route::get('/login-staff', [AuthController::class, 'showStaffLogin'])->name('login.staff');
Route::post('/login-staff', [AuthController::class, 'staffLogin'])->name('login.staff.submit');

// Authenticated Routes (Dashboard, Operasional Herbal & GIS)
Route::middleware('auth')->group(function () {
    // Dashboard Utama (otomatis mendeteksi role user, kurir, admin)
    Route::get('/', DashboardController::class)->name('dashboard');

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
