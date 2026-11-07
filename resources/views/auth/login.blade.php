@extends('layouts.auth', ['title' => 'Masuk'])

@section('content')
<div style="margin-bottom: 22px; text-align: center;">
    <h2 style="margin: 0 0 4px; font-family: Manrope, sans-serif; font-size: 22px; font-weight: 800; color: #202c29;">Masuk Sistem</h2>
    <p style="margin: 0; color: #78827d; font-size: 13px;">Kelola stok obat herbal, transaksi, dan pengiriman kurir</p>
</div>

<!-- Tombol Cepat Pilih Role (Klik untuk langsung mengisi) -->
<div style="margin-bottom: 20px; background: #f5f8f6; border: 1px solid #dce8e1; border-radius: 8px; padding: 12px;">
    <span style="display: block; font-size: 10px; font-weight: 700; color: #4b5953; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
        PILIH AKUN LOGIN (KLIK UNTUK ISI OTOMATIS):
    </span>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px;">
        <button type="button" onclick="selectRole('admin@kel3.test')" style="padding: 8px 4px; border: 1px solid #b8d9c7; background: #fff; border-radius: 6px; cursor: pointer; text-align: center; transition: all 0.15s;" onmouseover="this.style.background='#e8f3ed'" onmouseout="this.style.background='#fff'">
            <strong style="display: block; font-size: 11px; color: #176b53;">🛡️ ADMIN</strong>
            <small style="font-size: 9px; color: #78827d;">Full Akses</small>
        </button>
        <button type="button" onclick="selectRole('kurir1@kel3.test')" style="padding: 8px 4px; border: 1px solid #bae6fd; background: #fff; border-radius: 6px; cursor: pointer; text-align: center; transition: all 0.15s;" onmouseover="this.style.background='#f0f9ff'" onmouseout="this.style.background='#fff'">
            <strong style="display: block; font-size: 11px; color: #0284c7;">🚚 KURIR</strong>
            <small style="font-size: 9px; color: #78827d;">Tugas Antar</small>
        </button>
        <button type="button" onclick="selectRole('user@kel3.test')" style="padding: 8px 4px; border: 1px solid #fed7aa; background: #fff; border-radius: 6px; cursor: pointer; text-align: center; transition: all 0.15s;" onmouseover="this.style.background='#fff7ed'" onmouseout="this.style.background='#fff'">
            <strong style="display: block; font-size: 11px; color: #ea580c;">🌿 USER</strong>
            <small style="font-size: 9px; color: #78827d;">Pelanggan</small>
        </button>
    </div>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label for="email">Alamat Email</label>
        <input 
            type="email" 
            name="email" 
            id="email" 
            value="{{ old('email', 'admin@kel3.test') }}" 
            required 
            autofocus 
            autocomplete="username" 
            placeholder="nama@kel3.test"
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
        Masuk ke Sistem Herbal
    </button>
</form>

<div style="margin-top: 18px; text-align: center; font-size: 12px; color: #78827d;">
    Belum memiliki akun?
    <a href="{{ route('register') }}" style="color: #176b53; font-weight: 700;">Daftar akun baru</a>
</div>

<script>
    function selectRole(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
    }
</script>
@endsection
