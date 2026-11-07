@extends('layouts.app')

@section('title', 'Peta GIS Pengiriman Herbal')
@section('section', 'Geographic Information System (GIS)')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map {
        width: 100% !important;
        height: 620px !important;
        min-height: 620px !important;
        border-radius: 8px;
        z-index: 10;
        background: #e5e7eb;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .distance-tooltip {
        background: #203b33 !important;
        color: #fff !important;
        border: 1px solid #176b53 !important;
        border-radius: 6px !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        padding: 4px 8px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2) !important;
    }
    .distance-tooltip::before {
        border-top-color: #203b33 !important;
    }
</style>

<div class="gis-header-container" style="margin-bottom: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap;">
        <div>
            <p class="eyebrow">MONITORING GEOSPASIAL & DISTRIBUSI</p>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: #202c29; margin: 4px 0 6px;">
                Peta GIS Pengiriman Herbal
            </h1>
            <p style="font-size: 0.875rem; color: #78827d; margin: 0;">
                Pusat Gudang Utama di <strong>Indramayu</strong>. Klik titik pembeli (biru) untuk mengukur jarak & menarik garis pengiriman otomatis.
            </p>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('paket.create') }}" class="button button-primary">
                <span class="button-symbol">+</span> Tambah Titik / Paket
            </a>
            <a href="{{ route('paket.index') }}" class="button button-light">
                Kelola Tabel Paket
            </a>
        </div>
    </div>

    <!-- Filter Bar & Inspector Badge -->
    <div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 12px 18px; margin-top: 18px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
        <form method="GET" action="{{ route('gis.index') }}" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <span style="font-size: 11px; font-weight: 700; color: #4b5953; text-transform: uppercase;">Filter GIS:</span>
            
            <select name="status" onchange="this.form.submit()" style="min-height: 36px; padding: 0 10px; width: 170px;">
                <option value="">Semua Status</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                <option value="dikirim" {{ $status === 'dikirim' ? 'selected' : '' }}>Dikirim (Dalam Jalan)</option>
                <option value="terkirim" {{ $status === 'terkirim' ? 'selected' : '' }}>Terkirim (Selesai)</option>
            </select>

            <select name="kurir_id" onchange="this.form.submit()" style="min-height: 36px; padding: 0 10px; width: 180px;">
                <option value="">Semua Kurir</option>
                @foreach ($kurirs as $kurir)
                    <option value="{{ $kurir->id }}" {{ (string)$kurirId === (string)$kurir->id ? 'selected' : '' }}>
                        {{ $kurir->nama }}
                    </option>
                @endforeach
            </select>

            @if($status || $kurirId)
                <a class="clear-search" href="{{ route('gis.index') }}">Reset Filter</a>
            @endif
        </form>

        <!-- Current Selected Coord / Distance Preview Badge -->
        <div id="coord-badge" style="font-size: 11px; color: #176b53; font-weight: 600; background: #e8f3ed; padding: 6px 12px; border-radius: 6px; border: 1px solid #cce5d7;">
            📍 Pusat Usaha: <strong>Indramayu, Jawa Barat</strong> &bull; Klik titik pembeli (biru) untuk mengukur jarak
        </div>
    </div>

    <!-- Mini Ringkasan Metrik -->
    <div class="metrics-row" style="grid-template-columns: repeat(4, 1fr); margin-top: 14px; margin-bottom: 20px;">
        <div class="metric metric-dark">
            <span class="metric-label">PUSAT OPERASIONAL</span>
            <strong style="font-size: 20px; color: #176b53;">INDRAMAYU</strong>
            <span>Gudang Utama Kelompok 3</span>
        </div>
        <div class="metric metric-dark">
            <span class="metric-label">TITIK PEMBELI AKTIF</span>
            <strong>{{ $stats['total_lokasi'] }}</strong>
            <span>Titik biru terpetakan</span>
        </div>
        <div class="metric" style="color: #0369a1;">
            <span class="metric-label">SEDANG DIKIRIM</span>
            <strong style="color: #0369a1;">{{ $stats['dikirim'] }}</strong>
            <span>Dalam perjalanan kurir</span>
        </div>
        <div class="metric metric-green">
            <span class="metric-label">SUKSES TERKIRIM</span>
            <strong>{{ $stats['terkirim'] }}</strong>
            <span>Telah diterima pembeli</span>
        </div>
    </div>
</div>

<!-- Layout Peta + Daftar List Paket -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 18px; min-height: 640px;">
    
    <!-- Container Peta Leaflet -->
    <div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; overflow: hidden; position: relative; box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; flex-direction: column;">
        <div style="padding: 10px 16px; background: #f8faf8; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: #4b5953;">
            <span style="font-weight: 700; text-transform: uppercase;">
                🟢 Pusat Usaha Indramayu &bull; 🔵 Titik Pembeli Paket
            </span>
            <span style="color: #78827d;">Leaflet OpenStreetMap</span>
        </div>
        <div id="map"></div>
    </div>

    <!-- Sidebar Daftar Titik Paket -->
    <div style="background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 18px; display: flex; flex-direction: column; max-height: 665px; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #edf0ed; padding-bottom: 10px;">
            <h3 style="font-family: Manrope, sans-serif; font-size: 14px; font-weight: 800; color: #202c29; margin: 0;">Titik Pembeli</h3>
            <span style="font-size: 11px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 999px; font-weight: 700;">
                {{ count($pakets) }} Lokasi
            </span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            @forelse($pakets as $paket)
                <div class="paket-card" onclick="focusMarker({{ $paket->latitude }}, {{ $paket->longitude }}, '{{ $paket->no_resi }}')" style="border: 1px solid #e7ebe7; border-radius: 6px; padding: 12px; cursor: pointer; transition: all 0.2s; background: #fff;" onmouseover="this.style.borderColor='#0284c7'; this.style.backgroundColor='#f0f9ff';" onmouseout="this.style.borderColor='#e7ebe7'; this.style.backgroundColor='#fff';">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <span style="font-family: monospace; font-weight: 700; color: #0369a1; font-size: 12px;">{{ $paket->no_resi }}</span>
                        <span style="font-size: 9px; font-weight: 700; text-transform: uppercase; padding: 2px 6px; border-radius: 3px;
                            @if($paket->status === 'terkirim') background: #e8f3ed; color: #176b53;
                            @elseif($paket->status === 'dikirim') background: #e0f2fe; color: #0369a1;
                            @else background: #fef3c7; color: #b45309; @endif">
                            {{ $paket->status }}
                        </span>
                    </div>
                    <div style="font-size: 12px; font-weight: 700; color: #202c29;">{{ $paket->penerima }}</div>
                    <div style="font-size: 11px; color: #78827d; margin-top: 3px; line-height: 1.3;">{{ $paket->alamat_tujuan }}</div>
                    
                    <div style="margin-top: 8px; padding-top: 8px; border-top: 1px dashed #edf0ed; font-size: 10px; color: #818b85; display: flex; justify-content: space-between; align-items: center;">
                        <span>Kurir: <strong style="color: #36443e;">{{ $paket->kurir ? $paket->kurir->nama : 'Belum Ditugaskan' }}</strong></span>
                        <span style="font-family: monospace; color: #0284c7; font-weight: 600;">{{ number_format($paket->latitude, 3) }}, {{ number_format($paket->longitude, 3) }}</span>
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: var(--muted); font-size: 12px; padding: 30px 10px;">
                    Tidak ada titik paket pengiriman ditemukan.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Script Leaflet -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map;
    const markers = {};
    let activeLine = null;
    let tempMarker = null;

    // Titik Koordinat Pusat Usaha Herbal di Indramayu, Jawa Barat
    const INDRAMAYU_COORDS = [-6.3263889, 108.3200000];

    // Fungsi menghitung jarak rumus Haversine (kilometer)
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius bumi dalam KM
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
            Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return (R * c).toFixed(1);
    }

    function initMap() {
        const mapContainer = document.getElementById('map');
        if (!mapContainer || typeof L === 'undefined') {
            console.error('Leaflet belum siap.');
            return;
        }

        // Center peta pada Pulau Jawa & Nusantara
        map = L.map('map').setView([-6.5, 109.5], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap | Apotek Herbal GIS'
        }).addTo(map);

        setTimeout(() => {
            map.invalidateSize();
        }, 200);

        // Custom Marker Icon: Pusat Usaha di Indramayu (Hijau Emas)
        const hubIcon = L.divIcon({
            className: 'custom-hub-icon',
            html: `
                <div style="background: #176b53; border: 3px solid #ffffff; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.35); position: relative;">
                    🏢
                    <span style="position: absolute; top: -2px; right: -2px; width: 10px; height: 10px; background: #22c55e; border-radius: 50%; border: 1.5px solid #fff;"></span>
                </div>
            `,
            iconSize: [34, 34],
            iconAnchor: [17, 17],
            popupAnchor: [0, -18]
        });

        // Marker Pusat Usaha Indramayu
        const hubMarker = L.marker(INDRAMAYU_COORDS, { icon: hubIcon, zIndexOffset: 1000 })
            .addTo(map)
            .bindPopup(`
                <div style="font-family: inherit; font-size: 12px; line-height: 1.5; min-width: 210px;">
                    <div style="display:flex; align-items:center; gap:6px; margin-bottom:4px;">
                        <strong style="color: #176b53; font-size: 13px;">Gudang Pusat Herbal Kel 3</strong>
                        <span style="background:#e8f3ed; color:#176b53; font-size:9px; padding:2px 5px; border-radius:3px; font-weight:700;">PUSAT USAHA</span>
                    </div>
                    <div style="color: #334155;">📍 Jl. DI Panjaitan, Indramayu, Jawa Barat</div>
                    <div style="color: #64748b; font-size: 11px; margin-top: 4px;">Titik pangkal seluruh pengiriman ekspedisi paket obat herbal.</div>
                </div>
            `);

        // Custom Marker Icon: Pembeli Paket (Warna Biru)
        function createBuyerIcon() {
            return L.divIcon({
                className: 'custom-buyer-icon',
                html: `
                    <div style="background: #0284c7; border: 2.5px solid #ffffff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 11px; box-shadow: 0 3px 8px rgba(2,132,199,0.4);">
                        📍
                    </div>
                `,
                iconSize: [26, 26],
                iconAnchor: [13, 13],
                popupAnchor: [0, -14]
            });
        }

        const paketData = @json($pakets);

        if (paketData && paketData.length > 0) {
            const bounds = [INDRAMAYU_COORDS];

            paketData.forEach(function (item) {
                if (item.latitude && item.longitude) {
                    const lat = parseFloat(item.latitude);
                    const lng = parseFloat(item.longitude);
                    bounds.push([lat, lng]);

                    const distanceKm = calculateDistance(INDRAMAYU_COORDS[0], INDRAMAYU_COORDS[1], lat, lng);

                    let statusBadge = '<span style="background:#fef3c7;color:#b45309;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:700;">PENDING</span>';
                    if (item.status === 'dikirim') {
                        statusBadge = '<span style="background:#e0f2fe;color:#0369a1;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:700;">DIKIRIM</span>';
                    } else if (item.status === 'terkirim') {
                        statusBadge = '<span style="background:#e8f3ed;color:#176b53;padding:2px 6px;border-radius:4px;font-size:10px;font-weight:700;">TERKIRIM</span>';
                    }

                    const kurirNama = (item.kurir && item.kurir.nama) ? item.kurir.nama : 'Belum Ditugaskan';

                    const popupContent = `
                        <div style="font-family: inherit; font-size: 12px; line-height: 1.5; min-width: 220px;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 6px;">
                                <strong style="font-size: 13px; color: #0369a1;">${item.no_resi}</strong>
                                ${statusBadge}
                            </div>
                            <div style="color: #202c29;"><strong>Pembeli:</strong> ${item.penerima}</div>
                            <div style="color: #4b5953; margin-top: 3px;"><strong>Tujuan:</strong> ${item.alamat_tujuan}</div>
                            
                            <div style="margin-top: 6px; padding: 6px 8px; background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 6px; font-size: 11px; color: #0369a1;">
                                📏 <strong>Jarak dari Indramayu:</strong> ${distanceKm} km
                            </div>

                            <div style="margin-top: 6px; padding-top: 6px; border-top: 1px dashed #e7ebe7; font-size: 11px; color: #69746e;">
                                <div>Kurir: <strong>${kurirNama}</strong></div>
                                <div style="font-family: monospace; color: #0284c7; margin-top: 2px;">
                                    Lat: ${lat.toFixed(6)} | Lng: ${lng.toFixed(6)}
                                </div>
                            </div>
                            <div style="margin-top: 8px;">
                                <a href="/paket/${item.id}/edit" style="display:inline-block; padding: 4px 8px; background: #0284c7; color:#fff; border-radius: 4px; text-decoration: none; font-size: 10px; font-weight: 700;">
                                    Edit Paket
                                </a>
                            </div>
                        </div>
                    `;

                    const marker = L.marker([lat, lng], { icon: createBuyerIcon() })
                        .addTo(map)
                        .bindPopup(popupContent);

                    // Saat marker pembeli diklik: tarik garis lurus dari Indramayu ke titik pembeli
                    marker.on('click', function() {
                        drawDeliveryLine(lat, lng, item.no_resi, item.penerima, distanceKm);
                    });

                    markers[item.no_resi] = { marker, lat, lng, no_resi: item.no_resi, penerima: item.penerima, distanceKm };
                }
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        }

        // Fitur inspect koordinat saat klik di area peta mana saja
        map.on('click', function (e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            const distanceKm = calculateDistance(INDRAMAYU_COORDS[0], INDRAMAYU_COORDS[1], lat, lng);

            document.getElementById('coord-badge').innerHTML = `📍 Titik Diklik: <strong>Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}</strong> &bull; Jarak ke Indramayu: <strong>${distanceKm} km</strong>`;

            if (tempMarker) {
                map.removeLayer(tempMarker);
            }

            tempMarker = L.circleMarker(e.latlng, {
                radius: 8,
                fillColor: "#bc693f",
                color: "#fff",
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map);

            tempMarker.bindPopup(`
                <div style="font-size: 11px; line-height: 1.4;">
                    <strong>Titik Peta:</strong><br>
                    Lat: <code>${lat.toFixed(6)}</code> | Lng: <code>${lng.toFixed(6)}</code><br>
                    Jarak ke Indramayu: <strong>${distanceKm} km</strong><br>
                    <a href="/paket/create?lat=${lat.toFixed(6)}&lng=${lng.toFixed(6)}" style="display:inline-block; margin-top:6px; color:#176b53; font-weight:700;">
                        + Buat Paket di Titik Ini
                    </a>
                </div>
            `).openPopup();
        });
    }

    // Fungsi menggambar garis lurus jarak dari Indramayu ke Pembeli
    function drawDeliveryLine(destLat, destLng, noResi, penerima, distanceKm) {
        if (activeLine) {
            map.removeLayer(activeLine);
        }

        const latlngs = [
            INDRAMAYU_COORDS,
            [destLat, destLng]
        ];

        activeLine = L.polyline(latlngs, {
            color: '#0284c7',
            weight: 3.5,
            opacity: 0.85,
            dashArray: '8, 8',
            lineCap: 'round'
        }).addTo(map);

        // Tooltip di tengah garis jarak
        const midLat = (INDRAMAYU_COORDS[0] + destLat) / 2;
        const midLng = (INDRAMAYU_COORDS[1] + destLng) / 2;

        activeLine.bindTooltip(`📏 ${distanceKm} km (Indramayu &rarr; ${penerima})`, {
            permanent: true,
            direction: 'center',
            className: 'distance-tooltip'
        }).openTooltip();

        document.getElementById('coord-badge').innerHTML = `📍 Rute: <strong>Indramayu &rarr; ${noResi} (${penerima})</strong> &bull; Jarak: <strong>${distanceKm} km</strong>`;
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMap);
    } else {
        initMap();
    }

    function focusMarker(lat, lng, noResi) {
        if (map && markers[noResi]) {
            const data = markers[noResi];
            map.flyTo([lat, lng], 10, { duration: 1.2 });
            data.marker.openPopup();
            drawDeliveryLine(lat, lng, noResi, data.penerima, data.distanceKm);
        }
    }
</script>
@endsection
