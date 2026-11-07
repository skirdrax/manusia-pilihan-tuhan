@extends('layouts.app')

@section('title', 'Data Obat')
@section('section', 'Data obat')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">INVENTARIS APOTEK</p>
            <h1>Data obat</h1>
            <p class="page-description">Pantau ketersediaan dan informasi produk apotek.</p>
        </div>
        <a class="button button-primary" href="{{ route('medicines.create') }}"><span class="button-symbol">+</span> Tambah obat</a>
    </div>

    <div class="metrics-row">
        <div class="metric metric-green"><span class="metric-label">TOTAL PRODUK</span><strong>{{ number_format($medicineCount) }}</strong><span>jenis obat terdaftar</span></div>
        <div class="metric metric-orange"><span class="metric-label">STOK MENIPIS</span><strong>{{ number_format($lowStockCount) }}</strong><span>stok 10 atau kurang</span></div>
        <div class="metric metric-dark"><span class="metric-label">HASIL DITAMPILKAN</span><strong>{{ $medicines->total() }}</strong><span>sesuai pencarian</span></div>
    </div>

    <div class="section-toolbar">
        <div><h2>Daftar inventaris</h2><p>Informasi stok dan harga obat</p></div>
        <form class="search-form" action="{{ route('medicines.index') }}" method="GET">
            <label class="sr-only" for="medicine-search">Cari obat</label>
            <input id="medicine-search" type="search" name="q" value="{{ $search }}" placeholder="Cari nama, SKU, kategori...">
            <button class="button button-light" type="submit">Cari</button>
            @if ($search)<a class="clear-search" href="{{ route('medicines.index') }}">Reset</a>@endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>OBAT</th><th>KATEGORI</th><th>HARGA JUAL</th><th>STOK</th><th>KEDALUWARSA</th><th class="actions-heading">AKSI</th></tr></thead>
            <tbody>
                @forelse ($medicines as $medicine)
                    <tr>
                        <td><div class="medicine-cell"><span class="medicine-mark">{{ mb_strtoupper(mb_substr($medicine->name, 0, 1)) }}</span><span><strong>{{ $medicine->name }}</strong><small>{{ $medicine->sku }} · per {{ $medicine->unit }}</small>@if ($medicine->description)<small>{{ \Illuminate\Support\Str::limit($medicine->description, 45) }}</small>@endif</span></div></td>
                        <td>{{ $medicine->category ?: 'Tanpa kategori' }}</td>
                        <td class="price">Rp {{ number_format((float) $medicine->selling_price, 2, ',', '.') }}</td>
                        <td><span class="stock-badge {{ $medicine->stock <= 10 ? 'stock-low' : 'stock-ok' }}">{{ $medicine->stock }} {{ $medicine->unit }}</span></td>
                        <td>{{ $medicine->expiration_date?->format('d/m/Y') ?? '—' }}</td>
                        <td><div class="row-actions"><a class="icon-action" href="{{ route('medicines.edit', $medicine) }}" aria-label="Ubah {{ $medicine->name }}" title="Ubah data">✎</a><form action="{{ route('medicines.destroy', $medicine) }}" method="POST" data-confirm="Hapus {{ $medicine->name }} dari daftar obat?">@csrf @method('DELETE')<button class="icon-action danger-action" type="submit" aria-label="Hapus {{ $medicine->name }}" title="Hapus data">×</button></form></div></td>
                    </tr>
                @empty
                    <tr><td class="empty-state" colspan="6"><strong>Belum ada data obat</strong><span>{{ $search ? 'Coba kata pencarian lain.' : 'Tambahkan obat pertama untuk mulai mengelola inventaris.' }}</span></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($medicines->hasPages())
        <nav class="pagination" aria-label="Navigasi halaman"><span>Halaman {{ $medicines->currentPage() }} dari {{ $medicines->lastPage() }}</span><div>@if ($medicines->onFirstPage())<span class="page-disabled">Sebelumnya</span>@else<a href="{{ $medicines->previousPageUrl() }}">Sebelumnya</a>@endif @if ($medicines->hasMorePages())<a href="{{ $medicines->nextPageUrl() }}">Berikutnya</a>@else<span class="page-disabled">Berikutnya</span>@endif</div></nav>
    @endif
@endsection