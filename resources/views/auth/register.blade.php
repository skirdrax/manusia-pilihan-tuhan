@extends('layouts.auth', ['title' => 'Daftar'])

@section('content')
<div style="margin-bottom: 22px; text-align: center;">
    <h2 style="margin: 0 0 4px; font-family: Manrope, sans-serif; font-size: 22px; font-weight: 800; color: #202c29;">Pendaftaran Akun</h2>
    <p style="margin: 0; color: #78827d; font-size: 13px;">Daftarkan diri Anda untuk akses sistem herbal</p>
</div>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="form-group">
        <label for="name">Nama Lengkap</label>
        <input 
            type="text" 
            name="name" 
            id="name" 
            value="{{ old('name') }}" 
            required 
            autofocus 
            autocomplete="name" 
            placeholder="Nama lengkap Anda"
        >
    </div>

    <div class="form-group">
        <label for="email">Alamat Email</label>
        <input 
            type="email" 
            name="email" 
            id="email" 
            value="{{ old('email') }}" 
            required 
            autocomplete="username" 
            placeholder="nama@email.com"
        >
    </div>

    <div class="form-group">
        <label for="password">Kata Sandi (Min. 8 Karakter)</label>
        <input 
            type="password" 
            name="password" 
            id="password" 
            required 
            autocomplete="new-password" 
            placeholder="Minimal 8 karakter"
        >
    </div>

    <div class="form-group">
        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
        <input 
            type="password" 
            name="password_confirmation" 
            id="password_confirmation" 
            required 
            autocomplete="new-password" 
            placeholder="Ulangi kata sandi"
        >
    </div>

    <div style="margin-bottom: 20px;">
        <label style="display: flex; align-items: flex-start; gap: 8px; cursor: pointer; font-size: 12px; color: #69746e; margin: 0; text-transform: none; font-weight: 500;">
            <input type="checkbox" name="terms" id="terms" required style="width: auto; min-height: auto; margin-top: 3px;">
            <span>Saya menyetujui ketentuan layanan Apotek Herbal Kelompok 3</span>
        </label>
    </div>

    <button type="submit" class="button button-primary" style="width: 100%; min-height: 44px; font-size: 13px; font-weight: 700; border-radius: 6px;">
        Daftar Sekarang
    </button>
</form>

<div style="margin-top: 18px; text-align: center; font-size: 12px; color: #78827d;">
    Sudah memiliki akun?
    <a href="{{ route('login') }}" style="color: #176b53; font-weight: 700;">Masuk di sini</a>
</div>
@endsection
