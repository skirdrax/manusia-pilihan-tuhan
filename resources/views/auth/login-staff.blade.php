@extends('layouts.auth', ['title' => 'Portal Masuk Pengelola & Kurir'])

@section('content')
<div style="margin-bottom: 22px; text-align: center;">
    <span style="display: inline-block; font-size: 10px; font-weight: 700; color: #176b53; background: #e8f3ed; padding: 3px 8px; border-radius: 4px; text-transform: uppercase; margin-bottom: 8px;">
        AREA KHUSUS PETUGAS
    </span>
    <h2 style="margin: 0 0 4px; font-family: Manrope, sans-serif; font-size: 22px; font-weight: 800; color: #202c29;">Masuk Petugas & Kurir</h2>
    <p style="margin: 0; color: #78827d; font-size: 13px;">Login untuk Admin Pengelola Gudang atau Kurir Ekspedisi</p>
</div>

<form method="POST" action="{{ route('login.staff.submit') }}">
    @csrf

    <div class="form-group">
        <label for="email">Email Admin / Kurir</label>
        <input 
            type="email" 
            name="email" 
            id="email" 
            value="{{ old('email', 'admin@kel3.test') }}" 
            required 
            autofocus 
            autocomplete="username" 
            placeholder="admin@kel3.test atau kurir1@kel3.test"
        >
    </div>

    <div class="form-group">
        <label for="password">Kata Sandi</label>
        <input 
            type="password" 
            name="password" 
            id="password" 
            value="password"
            required 
            autocomplete="current-password" 
            placeholder="••••••••"
        >
    </div>

    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12px; color: #69746e; margin: 0; text-transform: none; font-weight: 500;">
            <input type="checkbox" name="remember" id="remember" style="width: auto; min-height: auto; margin: 0;">
            Ingat sesi masuk
        </label>
    </div>

    <button type="submit" class="button button-primary" style="width: 100%; min-height: 44px; font-size: 13px; font-weight: 700; border-radius: 6px;">
        Masuk Sebagai Petugas
    </button>
</form>

<div style="margin-top: 20px; text-align: center; font-size: 12px; color: #78827d;">
    Bukan petugas? 
    <a href="{{ route('dashboard') }}" style="color: #176b53; font-weight: 700;">Kembali ke Halaman Utama</a>
</div>
@endsection
