@extends('layouts.app')

@section('title', 'Data Pengiriman Paket')
@section('section', 'Operasional Pengiriman')

@php
    $userRole = auth()->user()->role ?? 'user';
@endphp

@section('content')
<div class="page-heading">
    <div>
        <p class="eyebrow">DISTRIBUSI HERBAL</p>
        <h1>Daftar Paket Pengiriman</h1>
        <p class="page-description">
            {{ $userRole === 'user' ? 'Lacak status dan informasi pengiriman paket herbal pesanan Anda.' : 'Kelola status ekspedisi, kurir pengantar, dan koordinat tujuan pengiriman paket herbal.' }}
        </p>
    </div>
    
    @if ($userRole === 'admin')
        <div style="display: flex; gap: 10px;">
            <a class="button button-light" href="{{ route('gis.index') }}">
                <span class="button-symbol">🗺</span> Lihat Peta GIS
            </a>
            <a class="button button-primary" href="{{ route('paket.create') }}">
                <span class="button-symbol">+</span> Tambah Paket Baru
            </a>
        </div>
    @endif
</div>

<div class="metrics-row" style="grid-template-columns: repeat(4, 1fr);">
    <div class="metric metric-dark">
        <span class="metric-label">TOTAL PAKET</span>
        <strong>{{ $stats['total'] }}</strong>
        <span>Tercatat di sistem</span>
    </div>
    <div class="metric metric-orange">
        <span class="metric-label">PENDING</span>
        <strong>{{ $stats['pending'] }}</strong>
        <span>Menunggu kurir</span>
    </div>
    <div class="metric" style="color: #2563eb;">
        <span class="metric-label">DIKIRIM</span>
        <strong style="color: #2563eb;">{{ $stats['dikirim'] }}</strong>
        <span>Dalam perjalanan</span>
    </div>
    <div class="metric metric-green">
        <span class="metric-label">TERKIRIM</span>
        <strong>{{ $stats['terkirim'] }}</strong>
        <span>Sampai ke tujuan</span>
    </div>
</div>

<div class="section-toolbar">
    <div>
        <h2>{{ $userRole === 'user' ? 'Lacak Paket Herbal' : 'Paket Terdaftar' }}</h2>
        <p>Gunakan pencarian untuk nomor resi, pengirim, penerima, atau alamat.</p>
    </div>
    <form class="search-form" method="GET" action="{{ route('paket.index') }}">
        <select name="status" onchange="this.form.submit()" style="min-height: 40px; padding: 0 10px; width: 140px;">
            <option value="">Semua Status</option>
            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="dikirim" {{ $status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
            <option value="terkirim" {{ $status === 'terkirim' ? 'selected' : '' }}>Terkirim</option>
        </select>
        <input type="search" name="q" value="{{ $search }}" placeholder="Cari nomor resi / nama...">
        <button class="button button-light" type="submit">Cari</button>
        @if ($search || $status)
            <a class="clear-search" href="{{ route('paket.index') }}">Reset</a>
        @endif
    </form>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>NO. RESI</th>
                <th>PENGIRIM</th>
                <th>PENERIMA & ALAMAT</th>
                <th>STATUS</th>
                <th>KURIR PENGANTAR</th>
                @if ($userRole !== 'user')
                    <th>KOORDINAT GIS</th>
                    <th style="text-align: right;">AKSI</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($pakets as $paket)
                <tr>
                    <td>
                        <strong style="font-family: monospace; font-size: 13px; color: #176b53;">{{ $paket->no_resi }}</strong>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #202c29;">{{ $paket->pengirim }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #202c29;">{{ $paket->penerima }}</div>
                        <div style="font-size: 11px; color: #78827d; max-width: 250px; overflow: hidden; text-overflow: ellipsis;">{{ $paket->alamat_tujuan }}</div>
                    </td>
                    <td>
                        <span style="display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase;
                            @if($paket->status === 'terkirim') background: #e8f3ed; color: #176b53;
                            @elseif($paket->status === 'dikirim') background: #e0f2fe; color: #0369a1;
                            @else background: #fef3c7; color: #b45309; @endif">
                            {{ $paket->status }}
                        </span>
                    </td>
                    <td>
                        @if ($paket->kurir)
                            <span style="font-weight: 600; color: #36443e;">{{ $paket->kurir->nama }}</span>
                        @else
                            <span style="color: #9eb4aa;">Belum Ditugaskan</span>
                        @endif
                    </td>

                    @if ($userRole !== 'user')
                        <td>
                            @if ($paket->latitude && $paket->longitude)
                                <a href="{{ route('gis.index') }}" style="display: inline-flex; align-items: center; gap: 4px; color: #176b53; font-weight: 600; font-size: 11px;">
                                    <span>📍</span> {{ number_format($paket->latitude, 3) }}, {{ number_format($paket->longitude, 3) }}
                                </a>
                            @else
                                <span style="color: #a4ada8;">-</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a class="button button-light" href="{{ route('paket.edit', $paket) }}" style="min-height: 30px; padding: 0 10px; font-size: 11px;">
                                    Edit
                                </a>
                                @if ($userRole === 'admin')
                                    <form action="{{ route('paket.destroy', $paket) }}" method="POST" data-confirm="Yakin ingin menghapus paket {{ $paket->no_resi }}?" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="button button-light" type="submit" style="min-height: 30px; padding: 0 10px; font-size: 11px; color: #bc693f;">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $userRole !== 'user' ? '7' : '5' }}" style="text-align: center; color: var(--muted); padding: 30px 15px;">
                        Tidak ada data paket pengiriman ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 16px;">
    {{ $pakets->links() }}
</div>
@endsection
