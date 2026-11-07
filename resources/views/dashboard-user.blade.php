@extends('layouts.app')

@section('title', 'Katalog Obat Herbal & Lacak Paket')
@section('section', 'Layanan Pelanggan')

@php
    $rp = fn ($n) => 'Rp '.number_format((float) $n, 0, ',', '.');
@endphp

@section('content')
<!-- Banner Selamat Datang Pelanggan -->
<div style="background: linear-gradient(135deg, #203b33 0%, #176b53 100%); border-radius: 10px; padding: 28px 32px; color: #fff; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
    <div>
        <span style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; background: rgba(255,255,255,0.15); padding: 4px 10px; border-radius: 99px;">
            PORTAL PELANGGAN
        </span>
        <h1 style="font-family: Manrope, sans-serif; font-size: 26px; font-weight: 800; margin: 10px 0 6px;">
            Selamat Datang, {{ auth()->user()->name }}! 🌿
        </h1>
        <p style="margin: 0; font-size: 13px; color: #d4e8df; max-width: 600px; line-height: 1.5;">
            Temukan aneka obat herbal alami pilihan berkualitas tinggi dan pantau status pengiriman paket pesanan Anda langsung ke rumah.
        </p>
    </div>
</div>

<!-- Kotak Cek Resi Cepat Pelanggan -->
<div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 22px; margin-bottom: 28px;">
    <h2 style="font-family: Manrope, sans-serif; font-size: 16px; font-weight: 800; color: #202c29; margin: 0 0 4px;">
        🔍 Lacak Status Paket Herbal Anda
    </h2>
    <p style="margin: 0 0 16px; font-size: 12px; color: var(--muted);">
        Masukkan nomor resi pengiriman Anda untuk melihat status perjalanan paket.
    </p>

    <form method="GET" action="{{ route('paket.index') }}" style="display: flex; gap: 10px; max-width: 550px;">
        <input type="text" name="q" placeholder="Masukkan Nomor Resi (misal: RESI-001, RESI-599)..." required style="border-radius: 6px;">
        <button type="submit" class="button button-primary" style="white-space: nowrap;">
            Cari Paket
        </button>
    </form>
</div>

<!-- Katalog Produk Herbal untuk Pelanggan -->
<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: baseline;">
    <div>
        <p class="eyebrow">KATALOG HERBAL PILIHAN</p>
        <h2 style="font-family: Manrope, sans-serif; font-size: 20px; font-weight: 800; color: #202c29; margin: 4px 0 0;">
            Daftar Obat Herbal Tersedia di Gudang
        </h2>
    </div>
    <span style="font-size: 12px; color: var(--muted);">Tersedia <strong>{{ count($medicines) }}</strong> produk herbal siap kirim</span>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 18px;">
    @forelse ($medicines as $item)
        <div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.15s, box-shadow 0.15s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="font-family: monospace; font-size: 11px; font-weight: 700; color: #176b53; background: #e8f3ed; padding: 2px 7px; border-radius: 4px;">
                        {{ $item->sku }}
                    </span>
                    <span style="font-size: 11px; color: #78827d;">
                        {{ $item->category }}
                    </span>
                </div>
                <h3 style="font-family: Manrope, sans-serif; font-size: 15px; font-weight: 700; color: #202c29; margin: 0 0 8px; line-height: 1.35;">
                    {{ $item->name }}
                </h3>
                <p style="font-size: 12px; color: #69746e; margin: 0 0 14px; line-height: 1.4;">
                    {{ $item->description ?? 'Produk herbal alami pilihan teruji khasiatnya untuk menjaga kesehatan dan vitalitas tubuh.' }}
                </p>
            </div>

            <div style="padding-top: 14px; border-top: 1px solid #edf0ed; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 10px; color: #818b85; display: block; font-weight: 700; text-transform: uppercase;">Harga Satuan</span>
                    <strong style="font-size: 16px; color: #176b53; font-family: Manrope, sans-serif;">{{ $rp($item->selling_price) }}</strong>
                    <span style="font-size: 11px; color: #78827d;">/ {{ $item->unit }}</span>
                </div>
                <div style="text-align: right;">
                    <span style="display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 10px; font-weight: 700;
                        @if($item->stock > 10) background: #e8f3ed; color: #176b53;
                        @elseif($item->stock > 0) background: #fef3c7; color: #b45309;
                        @else background: #fbefe8; color: #bc693f; @endif">
                        {{ $item->stock > 0 ? 'Stok: ' . $item->stock . ' ' . $item->unit : 'Stok Habis' }}
                    </span>
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--muted); background: #fff; border: 1px solid var(--line); border-radius: 8px;">
            Belum ada produk obat herbal yang ditampilkan.
        </div>
    @endforelse
</div>
@endsection
