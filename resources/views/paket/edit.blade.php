@extends('layouts.app')

@section('title', 'Edit Paket ' . $paket->no_resi)
@section('section', 'Operasional Pengiriman')

@section('content')
<div class="page-heading">
    <div>
        <p class="eyebrow">DISTRIBUSI HERBAL</p>
        <h1>Ubah Data Paket: {{ $paket->no_resi }}</h1>
        <p class="page-description">Perbarui status pengiriman, kurir penanggung jawab, atau koordinat lokasi tujuan.</p>
    </div>
    <div>
        <a class="button button-light" href="{{ route('paket.index') }}">
            &larr; Kembali ke Daftar
        </a>
    </div>
</div>

<div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 28px; max-width: 900px;">
    <form method="POST" action="{{ route('paket.update', $paket) }}">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-size: 11px; font-weight: 700; color: #4b5953; text-transform: uppercase; margin-bottom: 6px;">
                    Nomor Resi <span style="color: #bc693f;">*</span>
                </label>
                <input type="text" name="no_resi" value="{{ old('no_resi', $paket->no_resi) }}" required>
            </div>

            <div>
                <label style="display: block; font-size: 11px; font-weight: 700; color: #4b5953; text-transform: uppercase; margin-bottom: 6px;">
                    Status Pengiriman <span style="color: #bc693f;">*</span>
                </label>
                <select name="status" required>
                    <option value="pending" {{ old('status', $paket->status) === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="dikirim" {{ old('status', $paket->status) === 'dikirim' ? 'selected' : '' }}>Dikirim (Dalam Perjalanan)</option>
                    <option value="terkirim" {{ old('status', $paket->status) === 'terkirim' ? 'selected' : '' }}>Terkirim (Selesai)</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-size: 11px; font-weight: 700; color: #4b5953; text-transform: uppercase; margin-bottom: 6px;">
                    Nama Pengirim <span style="color: #bc693f;">*</span>
                </label>
                <input type="text" name="pengirim" value="{{ old('pengirim', $paket->pengirim) }}" required>
            </div>

            <div>
                <label style="display: block; font-size: 11px; font-weight: 700; color: #4b5953; text-transform: uppercase; margin-bottom: 6px;">
                    Nama Penerima <span style="color: #bc693f;">*</span>
                </label>
                <input type="text" name="penerima" value="{{ old('penerima', $paket->penerima) }}" required>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 11px; font-weight: 700; color: #4b5953; text-transform: uppercase; margin-bottom: 6px;">
                Alamat Tujuan Lengkap <span style="color: #bc693f;">*</span>
            </label>
            <textarea name="alamat_tujuan" rows="3" required>{{ old('alamat_tujuan', $paket->alamat_tujuan) }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 11px; font-weight: 700; color: #4b5953; text-transform: uppercase; margin-bottom: 6px;">
                Tugaskan Kurir
            </label>
            <select name="kurir_id">
                <option value="">-- Tanpa Kurir --</option>
                @foreach ($kurirs as $kurir)
                    <option value="{{ $kurir->id }}" {{ old('kurir_id', $paket->kurir_id) == $kurir->id ? 'selected' : '' }}>
                        {{ $kurir->nama }} ({{ $kurir->telepon }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Bagian Titik Koordinat GIS dengan Picker Peta Mini -->
        <div style="background: #f8faf8; border: 1px solid #e1e7e3; border-radius: 8px; padding: 18px; margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h3 style="margin: 0; font-family: Manrope, sans-serif; font-size: 14px; color: #176b53; font-weight: 700;">
                    📍 Penentuan Titik Koordinat GIS (Latitude & Longitude)
                </h3>
                <span style="font-size: 11px; color: #78827d;">Geser pin atau klik peta untuk memperbarui koordinat</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 10px; font-weight: 700; color: #69746e; text-transform: uppercase; margin-bottom: 4px;">Latitude</label>
                    <input type="number" step="any" id="lat-input" name="latitude" value="{{ old('latitude', $paket->latitude ?? -6.2087634) }}" placeholder="-6.2088">
                </div>
                <div>
                    <label style="display: block; font-size: 10px; font-weight: 700; color: #69746e; text-transform: uppercase; margin-bottom: 4px;">Longitude</label>
                    <input type="number" step="any" id="lng-input" name="longitude" value="{{ old('longitude', $paket->longitude ?? 106.8455990) }}" placeholder="106.8456">
                </div>
            </div>

            <!-- Mini Map Picker -->
            <div id="picker-map" style="width: 100%; height: 260px; border-radius: 6px; border: 1px solid #d4ded8;"></div>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="button button-primary">
                Simpan Perubahan
            </button>
            <a href="{{ route('paket.index') }}" class="button button-light">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const defaultLat = parseFloat(document.getElementById('lat-input').value) || -6.2087634;
        const defaultLng = parseFloat(document.getElementById('lng-input').value) || 106.8455990;

        const map = L.map('picker-map').setView([defaultLat, defaultLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        function updateInputs(lat, lng) {
            document.getElementById('lat-input').value = lat.toFixed(7);
            document.getElementById('lng-input').value = lng.toFixed(7);
        }

        marker.on('dragend', function (e) {
            const pos = e.target.getLatLng();
            updateInputs(pos.lat, pos.lng);
        });

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateInputs(e.latlng.lat, e.latlng.lng);
        });

        document.getElementById('lat-input').addEventListener('input', function() {
            const lat = parseFloat(this.value);
            const lng = parseFloat(document.getElementById('lng-input').value);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.panTo([lat, lng]);
            }
        });
        document.getElementById('lng-input').addEventListener('input', function() {
            const lat = parseFloat(document.getElementById('lat-input').value);
            const lng = parseFloat(this.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.panTo([lat, lng]);
            }
        });
    });
</script>
@endpush

