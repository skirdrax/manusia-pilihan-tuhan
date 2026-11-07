@extends('layouts.app')

@section('title', 'Penjualan')
@section('section', 'Penjualan')

@section('content')
    <div class="page-heading"><div><p class="eyebrow">KASIR APOTEK</p><h1>Penjualan</h1><p class="page-description">Riwayat transaksi dan penerimaan apotek.</p></div><a class="button button-primary" href="{{ route('sales.create') }}"><span class="button-symbol">+</span> Transaksi baru</a></div>
    <div class="section-toolbar"><div><h2>Riwayat transaksi</h2><p>{{ $sales->total() }} transaksi tercatat</p></div></div>
    <div class="table-wrap"><table><thead><tr><th>INVOICE</th><th>WAKTU</th><th>PELANGGAN</th><th>ITEM</th><th>PEMBAYARAN</th><th>TOTAL</th><th></th></tr></thead><tbody>
        @forelse ($sales as $sale)
            <tr><td><strong class="invoice-number">{{ $sale->invoice_number }}</strong></td><td>{{ $sale->sold_at->format('d/m/Y H:i') }}</td><td>{{ $sale->customer_name ?: 'Pelanggan umum' }}</td><td>{{ $sale->items->sum('quantity') }} item</td><td><span class="payment-badge">{{ ucfirst($sale->payment_method) }}</span></td><td class="price">Rp {{ number_format((float) $sale->total, 2, ',', '.') }}</td><td><a class="text-action" href="{{ route('sales.show', $sale) }}">Detail &rarr;</a></td></tr>
        @empty
            <tr><td class="empty-state" colspan="7"><strong>Belum ada transaksi</strong><span>Transaksi yang disimpan akan muncul di sini.</span></td></tr>
        @endforelse
    </tbody></table></div>
    @if ($sales->hasPages())<nav class="pagination" aria-label="Navigasi halaman"><span>Halaman {{ $sales->currentPage() }} dari {{ $sales->lastPage() }}</span><div>@if ($sales->onFirstPage())<span class="page-disabled">Sebelumnya</span>@else<a href="{{ $sales->previousPageUrl() }}">Sebelumnya</a>@endif @if ($sales->hasMorePages())<a href="{{ $sales->nextPageUrl() }}">Berikutnya</a>@else<span class="page-disabled">Berikutnya</span>@endif</div></nav>@endif
@endsection