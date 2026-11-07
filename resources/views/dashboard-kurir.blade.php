@extends('layouts.app')

@section('title', 'Tugas Pengiriman Kurir')
@section('section', 'Operasional Kurir')

@section('content')
<div style="background: linear-gradient(135deg, #1e3a8a 0%, #0369a1 100%); border-radius: 10px; padding: 26px 30px; color: #fff; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <span style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; background: rgba(255,255,255,0.18); padding: 4px 10px; border-radius: 99px;">
                DASHBOARD KURIR EKSPEDISI
            </span>
            <h1 style="font-family: Manrope, sans-serif; font-size: 24px; font-weight: 800; margin: 10px 0 6px;">
                Halo, {{ auth()->user()->name }}! 🚚
            </h1>
            <p style="margin: 0; font-size: 13px; color: #e0f2fe; max-width: 600px; line-height: 1.5;">
                Berikut adalah daftar paket obat herbal yang ditugaskan ke Anda. Anda dapat langsung membuka peta GIS untuk melihat rute dari Indramayu dan mengubah status pengiriman.
            </p>
        </div>
        <div>
            <a href="{{ route('gis.index') }}" class="button" style="background: #ffffff; color: #0369a1; font-weight: 700; border-radius: 6px; padding: 10px 18px;">
                🗺 Buka Peta Rute GIS
            </a>
        </div>
    </div>
</div>

<div class="metrics-row" style="grid-template-columns: repeat(3, minmax(0, 1fr)); margin-bottom: 22px;">
    <div class="metric metric-dark">
        <span class="metric-label">TOTAL TUGAS SAYA</span>
        <strong>{{ count($myPakets) }}</strong>
        <span>Paket tercatat</span>
    </div>
    <div class="metric" style="color: #0369a1;">
        <span class="metric-label">SEDANG DIANTAR</span>
        <strong style="color: #0369a1;">{{ $myPakets->where('status', 'dikirim')->count() }}</strong>
        <span>Dalam perjalanan</span>
    </div>
    <div class="metric metric-green">
        <span class="metric-label">SUKSES TERKIRIM</span>
        <strong>{{ $myPakets->where('status', 'terkirim')->count() }}</strong>
        <span>Selesai diantar</span>
    </div>
</div>

<div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 22px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div>
            <h2 style="font-family: Manrope, sans-serif; font-size: 16px; font-weight: 800; color: #202c29; margin: 0 0 2px;">
                Daftar Paket Tugas Kurir
            </h2>
            <p style="margin: 0; font-size: 11px; color: var(--muted);">Ubah status pengiriman saat barang selesai diantar.</p>
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
                    <th>STATUS SAAT INI</th>
                    <th style="text-align: right;">UBAH STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($myPakets as $pkt)
                    <tr>
                        <td style="font-family: monospace; font-weight: 700; color: #0369a1; font-size: 13px;">{{ $pkt->no_resi }}</td>
                        <td style="font-weight: 600; color: #202c29;">{{ $pkt->penerima }}</td>
                        <td style="font-size: 11px; color: #69746e; max-width: 250px; overflow: hidden; text-overflow: ellipsis;">{{ $pkt->alamat_tujuan }}</td>
                        <td>
                            @if ($pkt->latitude && $pkt->longitude)
                                <a href="{{ route('gis.index') }}" style="color: #0369a1; font-weight: 600; font-size: 11px;">
                                    📍 {{ number_format($pkt->latitude, 3) }}, {{ number_format($pkt->longitude, 3) }}
                                </a>
                            @else
                                <span style="color: #9eb4aa;">-</span>
                            @endif
                        </td>
                        <td>
                            <span style="display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase;
                                @if($pkt->status === 'terkirim') background: #e8f3ed; color: #176b53;
                                @elseif($pkt->status === 'dikirim') background: #e0f2fe; color: #0369a1;
                                @else background: #fef3c7; color: #b45309; @endif">
                                {{ $pkt->status }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <form action="{{ route('paket.update', $pkt) }}" method="POST" style="display: inline-flex; gap: 6px; align-items: center;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="no_resi" value="{{ $pkt->no_resi }}">
                                <input type="hidden" name="pengirim" value="{{ $pkt->pengirim }}">
                                <input type="hidden" name="penerima" value="{{ $pkt->penerima }}">
                                <input type="hidden" name="alamat_tujuan" value="{{ $pkt->alamat_tujuan }}">
                                <input type="hidden" name="latitude" value="{{ $pkt->latitude }}">
                                <input type="hidden" name="longitude" value="{{ $pkt->longitude }}">
                                <input type="hidden" name="kurir_id" value="{{ $pkt->kurir_id }}">

                                <select name="status" onchange="this.form.submit()" style="min-height: 30px; font-size: 11px; padding: 2px 8px; width: 115px; border-radius: 4px;">
                                    <option value="pending" {{ $pkt->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="dikirim" {{ $pkt->status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="terkirim" {{ $pkt->status === 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--muted); padding: 28px;">
                            Saat ini belum ada paket yang ditugaskan ke kurir Anda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

