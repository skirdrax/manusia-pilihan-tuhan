@extends('layouts.app')

@section('title', 'Dashboard Obat Herbal')
@section('section', 'Dashboard')

@php
    $rp = fn ($n) => 'Rp '.number_format((float) $n, 0, ',', '.');
    $soonLimit = today()->addDays(30);
@endphp

@section('content')
<style>
    .dash-actions { display: flex; flex-wrap: wrap; gap: 8px; }
    .metrics-row.metrics-four { grid-template-columns: repeat(4, minmax(0, 1fr)); margin-bottom: 16px; }
    .dash-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-bottom: 24px; }
    .dash-card { min-width: 0; padding: 20px 22px; border: 1px solid var(--line); border-radius: 6px; background: var(--paper); }
    .dash-card-head { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
    .dash-card-head h2 { margin: 0; font-family: Manrope, sans-serif; font-size: 15px; font-weight: 800; color: #202c29; }
    .dash-card-head p { margin: 4px 0 0; color: var(--muted); font-size: 11px; }
    .dash-card-head a { color: var(--green); font-size: 11px; font-weight: 700; white-space: nowrap; }
    .status-bar { display: flex; height: 12px; gap: 2px; overflow: hidden; border-radius: 99px; background: #edf0ed; }
    .status-bar span { display: block; min-width: 4px; }
    .seg-ok { background: #3f9a76; } .seg-low { background: #d99a55; } .seg-out { background: #b34f36; }
    .status-legend { display: grid; gap: 11px; margin: 18px 0 0; padding: 0; list-style: none; }
    .status-legend li { display: flex; align-items: center; gap: 10px; font-size: 12px; }
    .status-legend i { width: 10px; height: 10px; border-radius: 2px; }
    .status-legend b { margin-left: auto; font-variant-numeric: tabular-nums; }
    .cat-row { display: grid; grid-template-columns: minmax(90px, 150px) 1fr 28px; align-items: center; gap: 12px; padding: 7px 0; font-size: 12px; }
    .cat-row > span:first-child { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .cat-row > b { text-align: right; font-variant-numeric: tabular-nums; }
    .cat-track { height: 8px; overflow: hidden; border-radius: 99px; background: #edf0ed; }
    .cat-track span { display: block; height: 100%; border-radius: 99px; background: var(--green); }
    .item-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px solid #edf0ed; font-size: 12px; }
    .item-row:last-child { border-bottom: 0; }
    .item-row strong { color: #293630; font-size: 12px; }
    .item-row small { display: block; margin-top: 2px; color: var(--muted); font-size: 10px; }
    .card-empty { margin: 0; padding: 14px 0; color: var(--muted); font-size: 12px; }
    @media (max-width: 1100px) {
        .metrics-row.metrics-four { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .dash-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="page-heading">
    <div>
        <p class="eyebrow">OPERASIONAL OBAT HERBAL KELOMPOK 3</p>
        <h1>Dashboard Sistem Obat Herbal</h1>
        <p class="page-description">Monitoring stok produk herbal alami, kasir penjualan, dan status pengiriman kurir berbasis database kel3.</p>
    </div>
    <div class="dash-actions">
        <a class="button button-light" href="{{ route('gis.index') }}">🗺 Peta Pengiriman GIS</a>
        <a class="button button-light" href="{{ route('paket.index') }}">📦 Kelola Paket</a>
        <a class="button button-primary" href="{{ route('medicines.create') }}"><span class="button-symbol">+</span> Tambah Obat Herbal</a>
    </div>
</div>

<!-- Metrik Data Produk Herbal -->
<div class="metrics-row metrics-four">
    <div class="metric metric-green"><span class="metric-label">TOTAL PRODUK HERBAL</span><strong>{{ number_format($total, 0, ',', '.') }}</strong><span>jenis obat di database</span></div>
    <div class="metric metric-dark"><span class="metric-label">STOK UNIT TERSEDIA</span><strong>{{ number_format($units, 0, ',', '.') }}</strong><span>total seluruh kemasan</span></div>
    <div class="metric metric-dark"><span class="metric-label">VALUASI INVENTARIS</span><strong>{{ $rp($stockValue) }}</strong><span>harga beli produk</span></div>
    <div class="metric metric-orange"><span class="metric-label">PERLU PERHATIAN</span><strong>{{ number_format($attention, 0, ',', '.') }}</strong><span>stok &le; {{ $lowStockAt }} atau masa dekat</span></div>
</div>

<!-- Metrik Data Pengiriman Paket Herbal -->
<div class="metrics-row" style="grid-template-columns: repeat(4, minmax(0, 1fr)); margin-bottom: 24px;">
    <div class="metric metric-dark"><span class="metric-label">TOTAL PENGIRIMAN</span><strong>{{ $paketStats['total'] }}</strong><span>paket dalam database</span></div>
    <div class="metric metric-orange"><span class="metric-label">PAKET PENDING</span><strong>{{ $paketStats['pending'] }}</strong><span>menunggu proses kurir</span></div>
    <div class="metric" style="color: #0369a1;"><span class="metric-label">SEDANG DIKIRIM</span><strong style="color: #0369a1;">{{ $paketStats['dikirim'] }}</strong><span>dalam perjalanan</span></div>
    <div class="metric metric-green"><span class="metric-label">SUKSES TERKIRIM</span><strong>{{ $paketStats['terkirim'] }}</strong><span>diterima pelanggan</span></div>
</div>

<div class="dash-grid">
    <!-- Status Ketersediaan Stok -->
    <section class="dash-card">
        <div class="dash-card-head">
            <div>
                <h2>Status Ketersediaan Stok</h2>
                <p>Ambang batas menipis: {{ $lowStockAt }} unit atau kurang</p>
            </div>
            <a href="{{ route('medicines.index') }}">Lihat semua</a>
        </div>
        @if ($total > 0)
            <div class="status-bar" role="img" aria-label="Perbandingan status stok">
                @foreach (['ok' => 'seg-ok', 'low' => 'seg-low', 'out' => 'seg-out'] as $key => $class)
                    @if ($statusCounts[$key] > 0)<span class="{{ $class }}" style="width: {{ $statusCounts[$key] / $total * 100 }}%"></span>@endif
                @endforeach
            </div>
            <ul class="status-legend">
                <li><i class="seg-ok"></i> Stok aman <b>{{ $statusCounts['ok'] }} jenis</b></li>
                <li><i class="seg-low"></i> Stok menipis <b>{{ $statusCounts['low'] }} jenis</b></li>
                <li><i class="seg-out"></i> Stok habis <b>{{ $statusCounts['out'] }} jenis</b></li>
            </ul>
        @else
            <p class="card-empty">Belum ada data obat herbal. <a class="text-action" href="{{ route('medicines.create') }}">Tambah sekarang</a></p>
        @endif
    </section>

    <!-- Kategori Produk Herbal -->
    <section class="dash-card">
        <div class="dash-card-head">
            <div>
                <h2>Kategori Obat Herbal</h2>
                <p>Distribusi varian produk herbal alami</p>
            </div>
        </div>
        @forelse ($categories as $cat)
            <div class="cat-row">
                <span>{{ $cat->category }}</span>
                <div class="cat-track"><span style="width: {{ $maxCategory > 0 ? ($cat->total / $maxCategory * 100) : 0 }}%"></span></div>
                <b>{{ $cat->total }}</b>
            </div>
        @empty
            <p class="card-empty">Belum ada kategori obat herbal terdaftar.</p>
        @endforelse
    </section>
</div>

<!-- Tabel Pengiriman Paket Terkini dari Database -->
<div style="background: #fff; border: 1px solid var(--line); border-radius: 6px; padding: 22px; margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div>
            <h2 style="margin: 0; font-family: Manrope, sans-serif; font-size: 15px; font-weight: 800; color: #202c29;">Pengiriman Paket Herbal Terkini (Real DB)</h2>
            <p style="margin: 3px 0 0; color: var(--muted); font-size: 11px;">Data diambil langsung dari tabel <code>paket</code> dan <code>kurir</code> database kel3</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('gis.index') }}" class="button button-light" style="min-height: 32px; padding: 0 10px; font-size: 11px;">🗺 Buka di GIS</a>
            <a href="{{ route('paket.index') }}" class="button button-primary" style="min-height: 32px; padding: 0 10px; font-size: 11px;">Semua Paket &rarr;</a>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>NO. RESI</th>
                    <th>PENERIMA</th>
                    <th>ALAMAT TUJUAN</th>
                    <th>KOORDINAT GIS</th>
                    <th>KURIR</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latestPaket as $pkt)
                    <tr>
                        <td style="font-family: monospace; font-weight: 700; color: #176b53;">{{ $pkt->no_resi }}</td>
                        <td style="font-weight: 600; color: #202c29;">{{ $pkt->penerima }}</td>
                        <td style="color: #69746e; max-width: 250px; overflow: hidden; text-overflow: ellipsis;">{{ $pkt->alamat_tujuan }}</td>
                        <td>
                            @if ($pkt->latitude && $pkt->longitude)
                                <a href="{{ route('gis.index') }}" style="color: #176b53; font-weight: 600; font-size: 11px;">
                                    📍 {{ number_format($pkt->latitude, 3) }}, {{ number_format($pkt->longitude, 3) }}
                                </a>
                            @else
                                <span style="color: #9eb4aa;">-</span>
                            @endif
                        </td>
                        <td>
                            {{ $pkt->kurir ? $pkt->kurir->nama : 'Belum Ditugaskan' }}
                        </td>
                        <td>
                            <span style="display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase;
                                @if($pkt->status === 'terkirim') background: #e8f3ed; color: #176b53;
                                @elseif($pkt->status === 'dikirim') background: #e0f2fe; color: #0369a1;
                                @else background: #fef3c7; color: #b45309; @endif">
                                {{ $pkt->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--muted); padding: 20px;">Belum ada paket terdaftar di database.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
