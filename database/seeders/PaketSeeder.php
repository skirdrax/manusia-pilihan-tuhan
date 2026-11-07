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
                'pengirim' => 'Gudang Pusat Herbal Kelompok 3',
                'penerima' => 'Ahmad Fauzi (Apotek Sehat Alami)',
                'alamat_tujuan' => 'Jl. Jend. Sudirman No. 45, RT 02/RW 03, Jakarta Pusat',
                'latitude' => -6.2087634,
                'longitude' => 106.8455990,
                'status' => 'pending',
                'kurir_id' => null,
            ],
            [
                'no_resi' => 'RESI-002',
                'pengirim' => 'Apotek Herbal Kelompok 3',
                'penerima' => 'Siti Nurhaliza (Klinik Thibbun Nabawi)',
                'alamat_tujuan' => 'Jl. Ir. H. Juanda No. 12 (Dago), Bandung, Jawa Barat',
                'latitude' => -6.9174639,
                'longitude' => 107.6191228,
                'status' => 'dikirim',
                'kurir_id' => 1,
            ],
            [
                'no_resi' => 'RESI-003',
                'pengirim' => 'Distributor Madu & Habbatussauda Kel 3',
                'penerima' => 'Bambang Pratama',
                'alamat_tujuan' => 'Jl. Malioboro No. 88, Sosromenduran, Yogyakarta',
                'latitude' => -7.7955798,
                'longitude' => 110.3694896,
                'status' => 'terkirim',
                'kurir_id' => 2,
            ],
            [
                'no_resi' => 'RESI-004',
                'pengirim' => 'Apotek Herbal Kelompok 3',
                'penerima' => 'Dewi Lestari (Griya Jamu Solo)',
                'alamat_tujuan' => 'Jl. Slamet Riyadi No. 101, Surakarta, Jawa Tengah',
                'latitude' => -7.5665392,
                'longitude' => 110.8267865,
                'status' => 'pending',
                'kurir_id' => null,
            ],
            [
                'no_resi' => 'RESI-005',
                'pengirim' => 'Pusat Distribusi Obat Herbal Kel 3',
                'penerima' => 'Rian Hidayat (Toko Obat Herbal Barokah)',
                'alamat_tujuan' => 'Jl. Tunjungan No. 5, Genteng, Surabaya, Jawa Timur',
                'latitude' => -7.2574719,
                'longitude' => 112.7520883,
                'status' => 'dikirim',
                'kurir_id' => 1,
            ],
            [
                'no_resi' => 'RESI-006',
                'pengirim' => 'Apotek Herbal Kelompok 3',
                'penerima' => 'Indah Permatasari',
                'alamat_tujuan' => 'Jl. Pandanaran No. 30, Mugassari, Semarang, Jawa Tengah',
                'latitude' => -6.9932000,
                'longitude' => 110.4203000,
                'status' => 'terkirim',
                'kurir_id' => 2,
            ],
            [
                'no_resi' => 'RESI-007',
                'pengirim' => 'Sentral Suplemen Herbal Kel 3',
                'penerima' => 'Eko Prasetyo (Herbalis Malang)',
                'alamat_tujuan' => 'Jl. Besar Ijen No. 20, Klojen, Malang, Jawa Timur',
                'latitude' => -7.9666204,
                'longitude' => 112.6326321,
                'status' => 'pending',
                'kurir_id' => null,
            ],
            [
                'no_resi' => 'RESI-008',
                'pengirim' => 'Apotek Herbal Kelompok 3',
                'penerima' => 'Putu Suardana (Natural Health Bali)',
                'alamat_tujuan' => 'Jl. Raya Kuta No. 77, Kuta, Badung, Bali',
                'latitude' => -8.7231450,
                'longitude' => 115.1768400,
                'status' => 'dikirim',
                'kurir_id' => 1,
            ],
            [
                'no_resi' => 'RESI-009',
                'pengirim' => 'Apotek Herbal Kelompok 3',
                'penerima' => 'Tengku Iskandar (Apotek Madu Deli)',
                'alamat_tujuan' => 'Jl. Balai Kota No. 15, Kesawan, Medan Barat, Kota Medan',
                'latitude' => 3.5951956,
                'longitude' => 98.6722227,
                'status' => 'terkirim',
                'kurir_id' => 2,
            ],
            [
                'no_resi' => 'RESI-010',
                'pengirim' => 'Depo Rempah & Jamu Kelompok 3',
                'penerima' => 'Andi Nurul (Griya Sehat Makassar)',
                'alamat_tujuan' => 'Jl. Penghibur No. 9 (Pantai Losari), Ujung Pandang, Makassar',
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
