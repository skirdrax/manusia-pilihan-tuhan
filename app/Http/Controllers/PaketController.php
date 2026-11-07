<?php

namespace App\Http\Controllers;

use App\Models\Kurir;
use App\Models\Paket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaketController extends Controller
{
    /**
     * Tampilkan daftar paket pengiriman herbal
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $status = $request->query('status');

        $query = Paket::with('kurir');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_resi', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%")
                  ->orWhere('penerima', 'like', "%{$search}%")
                  ->orWhere('alamat_tujuan', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $pakets = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total'    => Paket::count(),
            'pending'  => Paket::where('status', 'pending')->count(),
            'dikirim'  => Paket::where('status', 'dikirim')->count(),
            'terkirim' => Paket::where('status', 'terkirim')->count(),
        ];

        return view('paket.index', compact('pakets', 'stats', 'search', 'status'));
    }

    /**
     * Form tambah paket baru
     */
    public function create(): View
    {
        $kurirs = Kurir::orderBy('nama')->get();
        return view('paket.create', compact('kurirs'));
    }

    /**
     * Simpan paket baru ke database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'no_resi'       => ['required', 'string', 'max:30', 'unique:paket,no_resi'],
            'pengirim'      => ['required', 'string', 'max:100'],
            'penerima'      => ['required', 'string', 'max:100'],
            'alamat_tujuan' => ['required', 'string'],
            'latitude'      => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'     => ['nullable', 'numeric', 'between:-180,180'],
            'status'        => ['required', 'in:pending,dikirim,terkirim'],
            'kurir_id'      => ['nullable', 'exists:kurir,id'],
        ]);

        Paket::create($validated);

        return redirect()->route('paket.index')->with('success', 'Paket pengiriman herbal ' . $validated['no_resi'] . ' berhasil ditambahkan.');
    }

    /**
     * Form edit paket
     */
    public function edit(Paket $paket): View
    {
        $kurirs = Kurir::orderBy('nama')->get();
        return view('paket.edit', compact('paket', 'kurirs'));
    }

    /**
     * Update paket
     */
    public function update(Request $request, Paket $paket): RedirectResponse
    {
        $validated = $request->validate([
            'no_resi'       => ['required', 'string', 'max:30', 'unique:paket,no_resi,' . $paket->id],
            'pengirim'      => ['required', 'string', 'max:100'],
            'penerima'      => ['required', 'string', 'max:100'],
            'alamat_tujuan' => ['required', 'string'],
            'latitude'      => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'     => ['nullable', 'numeric', 'between:-180,180'],
            'status'        => ['required', 'in:pending,dikirim,terkirim'],
            'kurir_id'      => ['nullable', 'exists:kurir,id'],
        ]);

        $paket->update($validated);

        return redirect()->route('paket.index')->with('success', 'Data paket ' . $paket->no_resi . ' berhasil diperbarui.');
    }

    /**
     * Hapus paket
     */
    public function destroy(Paket $paket): RedirectResponse
    {
        $noResi = $paket->no_resi;
        $paket->delete();

        return redirect()->route('paket.index')->with('success', 'Paket ' . $noResi . ' berhasil dihapus.');
    }
}

