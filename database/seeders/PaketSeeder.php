<?php

namespace Database\Seeders;

use App\Models\Paket;
use Illuminate\Database\Seeder;

class PaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pakets = [
            [
                'no_resi' => 'RESI-001',
                'pengirim' => 'PT Sumber Makmur',
                'penerima' => 'Ahmad Fauzi',
                'alamat_tujuan' => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'latitude' => -6.2087634,
                'longitude' => 106.8455990,
                'status' => 'pending',
                'kurir_id' => null,
            ],
            [
                'no_resi' => 'RESI-002',
                'pengirim' => 'Toko Buku Cerdas',
                'penerima' => 'Siti Nurhaliza',
                'alamat_tujuan' => 'Jl. Dago No. 12, Bandung',
                'latitude' => -6.9174639,
                'longitude' => 107.6191228,
                'status' => 'dikirim',
                'kurir_id' => 1,
            ],
            [
                'no_resi' => 'RESI-003',
                'pengirim' => 'Elektronik Maju Jaya',
                'penerima' => 'Bambang Pratama',
                'alamat_tujuan' => 'Jl. Malioboro No. 88, Yogyakarta',
                'latitude' => -7.7955798,
                'longitude' => 110.3694896,
                'status' => 'terkirim',
                'kurir_id' => 2,
            ],
            [
                'no_resi' => 'RESI-004',
                'pengirim' => 'Distro Gaul Solo',
                'penerima' => 'Dewi Lestari',
                'alamat_tujuan' => 'Jl. Slamet Riyadi No. 101, Surakarta',
                'latitude' => -7.5665392,
                'longitude' => 110.8267865,
                'status' => 'pending',
                'kurir_id' => null,
            ],
            [
                'no_resi' => 'RESI-005',
                'pengirim' => 'Surabaya Snack Corner',
                'penerima' => 'Rian Hidayat',
                'alamat_tujuan' => 'Jl. Tunjungan No. 5, Surabaya',
                'latitude' => -7.2574719,
                'longitude' => 112.7520883,
                'status' => 'dikirim',
                'kurir_id' => 1,
            ],
            [
                'no_resi' => 'RESI-006',
                'pengirim' => 'Semarang Fashion Hub',
                'penerima' => 'Indah Permatasari',
                'alamat_tujuan' => 'Jl. Pandanaran No. 30, Semarang',
                'latitude' => -6.9932000,
                'longitude' => 110.4203000,
                'status' => 'terkirim',
                'kurir_id' => 2,
            ],
            [
                'no_resi' => 'RESI-007',
                'pengirim' => 'Sentral Gadget Malang',
                'penerima' => 'Eko Prasetyo',
                'alamat_tujuan' => 'Jl. Ijen No. 20, Malang',
                'latitude' => -7.9666204,
                'longitude' => 112.6326321,
                'status' => 'pending',
                'kurir_id' => null,
            ],
            [
                'no_resi' => 'RESI-008',
                'pengirim' => 'Bali Craft Workshop',
                'penerima' => 'Putu Suardana',
                'alamat_tujuan' => 'Jl. Raya Kuta No. 77, Badung, Bali',
                'latitude' => -8.7231450,
                'longitude' => 115.1768400,
                'status' => 'dikirim',
                'kurir_id' => 1,
            ],
            [
                'no_resi' => 'RESI-009',
                'pengirim' => 'Medan Coffee Roastery',
                'penerima' => 'Tengku Iskandar',
                'alamat_tujuan' => 'Jl. Merdeka No. 15, Medan',
                'latitude' => 3.5951956,
                'longitude' => 98.6722227,
                'status' => 'terkirim',
                'kurir_id' => 2,
            ],
            [
                'no_resi' => 'RESI-010',
                'pengirim' => 'Makassar Herbal Store',
                'penerima' => 'Andi Nurul',
                'alamat_tujuan' => 'Jl. Pantai Losari No. 9, Makassar',
                'latitude' => -5.1476651,
                'longitude' => 119.4327314,
                'status' => 'dikirim',
                'kurir_id' => 1,
            ],
        ];

        foreach ($pakets as $data) {
            Paket::updateOrCreate(
                ['no_resi' => $data['no_resi']],
                $data
            );
        }
    }
}

