<?php

namespace App\Http\Controllers;

use App\Models\Kurir;
use App\Models\Paket;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GisController extends Controller
{
    /**
     * Tampilkan halaman Peta GIS Pengiriman Obat Herbal & Paket
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $kurirId = $request->query('kurir_id');

        $query = Paket::with('kurir')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($status) {
            $query->where('status', $status);
        }

        if ($kurirId) {
            $query->where('kurir_id', $kurirId);
        }

        $pakets = $query->latest()->get();
        $kurirs = Kurir::orderBy('nama')->get();

        $stats = [
            'total_lokasi' => Paket::whereNotNull('latitude')->whereNotNull('longitude')->count(),
            'dikirim' => Paket::where('status', 'dikirim')->count(),
            'terkirim' => Paket::where('status', 'terkirim')->count(),
            'pending' => Paket::where('status', 'pending')->count(),
        ];

        return view('gis.index', compact('pakets', 'kurirs', 'stats', 'status', 'kurirId'));
    }

    /**
     * Endpoint API JSON titik koordinat paket (untuk Leaflet)
     */
    public function points(Request $request): JsonResponse
    {
        $query = Paket::with('kurir:id,nama,telepon')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kurir_id')) {
            $query->where('kurir_id', $request->kurir_id);
        }

        return response()->json($query->get());
    }

    /**
     * Endpoint API JSON data Region / Poligon wilayah
     */
    public function regions(): JsonResponse
    {
        $regions = Region::select('id', 'gadm_id', 'name', 'geometry')->get();
        return response()->json($regions);
    }
}

