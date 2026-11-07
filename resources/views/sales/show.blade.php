@extends('layouts.app')

@section('title', 'Rincian Penjualan')
@section('section', 'Rincian transaksi')

@section('content')
    <div class="page-heading"><div><p class="eyebrow">KASIR APOTEK</p><h1>Rincian transaksi</h1><p class="page-description">{{ $sale->invoice_number }} · {{ $sale->sold_at->format('d F Y, H:i') }}</p></div><a class="button button-light" href="{{ route('sales.index') }}">Kembali ke penjualan</a></div>
    <div class="detail-strip"><div><span>PELANGGAN</span><strong>{{ $sale->customer_name ?: 'Pelanggan umum' }}</strong></div><div><span>PEMBAYARAN</span><strong>{{ ucfirst($sale->payment_method) }}</strong></div><div><span>NOMOR INVOICE</span><strong>{{ $sale->invoice_number }}</strong></div></div>
    <div class="section-toolbar"><div><h2>Rincian obat</h2><p>{{ $sale->items->sum('quantity') }} item</p></div></div>
    <div class="table-wrap"><table><thead><tr><th>OBAT</th><th>HARGA SATUAN</th><th>JUMLAH</th><th class="align-right">SUBTOTAL</th></tr></thead><tbody>@foreach ($sale->items as $item)<tr><td><strong>{{ $item->medicine?->name ?? 'Obat dihapus' }}</strong></td><td>Rp {{ number_format((float) $item->unit_price, 2, ',', '.') }}</td><td>{{ $item->quantity }}</td><td class="align-right price">Rp {{ number_format((float) $item->subtotal, 2, ',', '.') }}</td></tr>@endforeach</tbody><tfoot><tr><th colspan="3">TOTAL</th><th class="align-right grand-total">Rp {{ number_format((float) $sale->total, 2, ',', '.') }}</th></tr></tfoot></table></div>
@endsection