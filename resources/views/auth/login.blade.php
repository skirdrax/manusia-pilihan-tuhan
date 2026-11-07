@extends('layouts.auth', ['title' => 'Masuk Pelanggan'])

@section('content')
<div style="margin-bottom: 22px; text-align: center;">
    <div style="display: flex; justify-content: center; margin-bottom: 8px;">
        <span style="font-size: 11px; font-weight: 700; color: #176b53; background: #e8f3ed; padding: 3px 10px; border-radius: 99px; text-transform: uppercase;">
            PORTAL KHUSUS PELANGGAN
        </span>
    </div>
    <h2 style="margin: 0 0 4px; font-family: Manrope, sans-serif; font-size: 22px; font-weight: 800; color: #202c29;">Masuk Akun Pelanggan</h2>
    <p style="margin: 0; color: #78827d; font-size: 13px;">Silakan masuk untuk mengakses katalog obat herbal & lacak pengiriman paket Anda</p>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label for="email">Alamat Email Pelanggan</label>
        <input 
            type="email" 
            name="email" 
            id="email" 
            value="{{ old('email', 'user@kel3.test') }}" 
            required 
            autofocus 
            autocomplete="username" 
            placeholder="user@kel3.test"
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
            Ingat sesi saya
        </label>
    </div>

    <button type="submit" class="button button-primary" style="width: 100%; min-height: 44px; font-size: 13px; font-weight: 700; border-radius: 6px;">
        Masuk Sebagai Pelanggan
    </button>
</form>

<div style="margin-top: 18px; text-align: center; font-size: 12px; color: #78827d;">
    Belum memiliki akun pelanggan?
    <a href="{{ route('register') }}" style="color: #176b53; font-weight: 700;">Daftar akun di sini</a>
</div>
@endsection
