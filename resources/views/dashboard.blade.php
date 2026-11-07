@extends('layouts.app')

@section('title', 'Dashboard')
@section('section', 'Dashboard')

@php
    $rp = fn ($n) => 'Rp '.number_format((float) $n, 0, ',', '.');
    $soonLimit = today()->addDays(30);
@endphp

@section('content')
<style>
    .dash-actions { display: flex; flex-wrap: wrap; gap: 8px; }
    .metrics-row.metrics-four { grid-template-columns: repeat(4, minmax(0, 1fr)); margin-bottom: 16px; }
    .dash-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-bottom: 16px; }
    .dash-card { min-width: 0; padding: 20px 22px; border: 1px solid var(--line); border-radius: 6px; background: var(--paper); }
    .dash-card-head { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
    .dash-card-head h2 { margin: 0; font-family: Manrope, sans-serif; font-size: 15px; font-weight: 800; }
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
    .latest-section { margin-top: 8px; }
    .num-right { text-align: right; }
    @media (max-width: 1100px) {
        .metrics-row.metrics-four { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .dash-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="page-heading">
    <div>
        <p class="eyebrow">INFORMASI PRODUK</p>
        <h1>Dashboard</h1>
        <p class="page-description">Ringkasan stok, kategori, dan masa kedaluwarsa obat.</p>
    </div>
    <div class="dash-actions">
        <a class="button button-light" href="{{ route('medicines.index') }}">Kelola obat</a>
        <a class="button button-primary" href="{{ route('medicines.create') }}"><span class="button-symbol">+</span> Tambah obat</a>
    </div>
</div>

<div class="metrics-row metrics-four">
    <div class="metric metric-green"><span class="metric-label">TOTAL OBAT</span><strong>{{ number_format($total, 0, ',', '.') }}</strong><span>jenis obat terdaftar</span></div>
    <div class="metric metric-dark"><span class="metric-label">TOTAL STOK</span><strong>{{ number_format($units, 0, ',', '.') }}</strong><span>jumlah stok seluruh obat</span></div>
    <div class="metric metric-dark"><span class="metric-label">NILAI STOK</span><strong>{{ $rp($stockValue) }}</strong><span>berdasarkan harga beli</span></div>
    <div class="metric metric-orange"><span class="metric-label">PERLU PERHATIAN</span><strong>{{ number_format($attention, 0, ',', '.') }}</strong><span>stok menipis atau kedaluwarsa dekat</span></div>
</div>

<div class="dash-grid">
    <section class="dash-card">
        <div class="dash-card-head"><div><h2>Status stok</h2><p>Menipis berarti stok {{ $lowStockAt }} atau kurang</p></div></div>
        @if ($total > 0)
            <div class="status-bar" role="img" aria-label="Perbandingan status stok">
                @foreach (['ok' => 'seg-ok', 'low' => 'seg-low', 'out' => 'seg-out'] as $key => $class)
                    @if ($statusCounts[$key] > 0)<span class="{{ $class }}" style="width: {{ $statusCounts[$key] / $total * 100 }}%"></span>@endif
                @endforeach
            </div>
            <ul class="status-legend">
                <li><i class="seg-ok"></i> Stok aman <b>{{ $statusCounts['ok'] }}</b></li>
                <li><i class="seg-low"></i> Stok menipis <b>{{ $statusCounts['low'] }}</b></li>
                <li><i class="seg-out"></i> Stok habis <b>{{ $statusCounts['out'] }}</b></li>
            </ul>
        @else
            <p class="card-empty">Belum ada data obat. <a class="text-action" href="{{ route('medicines.create') }}">Tambah obat pertama</a></p>
        @endif
    </section>

    <section class="dash-card">
        <div class="dash-card-head"><div><h2>Obat per kategori</h2><p>Jumlah jenis obat di tiap kategori</p></div></div>
        @forelse ($categories as $row)
            <div class="cat-row">
                <span title="{{ $row->category ?: 'Tanpa kategori' }}">{{ $row->category ?: 'Tanpa kategori' }}</span>
                <div class="cat-track"><span style="width: {{ $maxCategory > 0 ? $row->total / $maxCategory * 100 : 0 }}%"></span></div>
                <b>{{ $row->total }}</b>
            </div>
        @empty
            <p class="card-empty">Belum ada kategori.</p>
        @endforelse
    </section>

    <section class="dash-card">
        <div class="dash-card-head"><div><h2>Perlu restok</h2><p>Stok {{ $lowStockAt }} atau kurang</p></div></div>
        @forelse ($restock as $medicine)
            <a class="item-row" href="{{ route('medicines.edit', $medicine) }}">
                <span><strong>{{ $medicine->name }}</strong><small>{{ $medicine->sku }} · {{ $medicine->category ?: 'Tanpa kategori' }}</small></span>
                <span class="stock-badge stock-low">{{ $medicine->stock === 0 ? 'Habis' : $medicine->stock.' '.$medicine->unit }}</span>
            </a>
        @empty
            <p class="card-empty">Semua stok aman.</p>
        @endforelse
    </section>

    <section class="dash-card">
        <div class="dash-card-head"><div><h2>Masa kedaluwarsa</h2><p>Sudah lewat atau dalam {{ $expiringDays }} hari</p></div></div>
        @forelse ($expiring as $medicine)
            <a class="item-row" href="{{ route('medicines.edit', $medicine) }}">
                <span><strong>{{ $medicine->name }}</strong><small>{{ $medicine->sku }} · stok {{ $medicine->stock }} {{ $medicine->unit }}</small></span>
                <span class="stock-badge {{ $medicine->expiration_date->lte($soonLimit) ? 'stock-low' : 'stock-ok' }}">{{ $medicine->expiration_date->isPast() ? 'Lewat ' : '' }}{{ $medicine->expiration_date->format('d/m/Y') }}</span>
            </a>
        @empty
            <p class="card-empty">Tidak ada obat yang mendekati kedaluwarsa.</p>
        @endforelse
    </section>
</div>

<div class="latest-section">
    <div class="section-toolbar">
        <div><h2>Obat terbaru</h2><p>Lima obat yang terakhir ditambahkan</p></div>
        <a class="text-action" href="{{ route('medicines.index') }}">Lihat semua obat &rarr;</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>OBAT</th><th>KATEGORI</th><th>HARGA JUAL</th><th>STOK</th><th>KEDALUWARSA</th><th class="actions-heading">AKSI</th></tr></thead>
            <tbody>
            @forelse ($latest as $medicine)
                <tr>
                    <td><div class="medicine-cell"><span class="medicine-mark">{{ mb_strtoupper(mb_substr($medicine->name, 0, 1)) }}</span><span><strong>{{ $medicine->name }}</strong><small>{{ $medicine->sku }} · per {{ $medicine->unit }}</small></span></div></td>
                    <td>{{ $medicine->category ?: 'Tanpa kategori' }}</td>
                    <td class="price">{{ $rp($medicine->selling_price) }}</td>
                    <td><span class="stock-badge {{ $medicine->stock <= $lowStockAt ? 'stock-low' : 'stock-ok' }}">{{ $medicine->stock }} {{ $medicine->unit }}</span></td>
                    <td>{{ $medicine->expiration_date?->format('d/m/Y') ?? '—' }}</td>
                    <td><div class="row-actions"><a class="icon-action" href="{{ route('medicines.edit', $medicine) }}" aria-label="Ubah {{ $medicine->name }}" title="Ubah data">✎</a></div></td>
                </tr>
            @empty
                <tr><td class="empty-state" colspan="6"><strong>Belum ada data obat</strong><span>Tambahkan obat pertama untuk mulai mengelola inventaris.</span></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection