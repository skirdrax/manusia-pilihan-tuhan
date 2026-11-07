<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            [
                'sku' => 'HBL-001',
                'name' => 'Minyak Habbatussauda Extra Propolis Trigona',
                'category' => 'Herbal & Alami',
                'unit' => 'botol',
                'purchase_price' => 45000,
                'selling_price' => 65000,
                'stock' => 25,
                'expiration_date' => now()->addMonths(12),
                'description' => 'Minyak jintan hitam murni kaya antioksidan dan propolis untuk daya tahan tubuh.',
            ],
            [
                'sku' => 'HBL-002',
                'name' => 'Madu Murni Randu Hutan Organik 500g',
                'category' => 'Madu & Olahan',
                'unit' => 'jar',
                'purchase_price' => 50000,
                'selling_price' => 75000,
                'stock' => 15,
                'expiration_date' => now()->addMonths(18),
                'description' => 'Madu mentah nektar bunga pohon randu tanpa bahan pengawet.',
            ],
            [
                'sku' => 'HBL-003',
                'name' => 'Kapsul Ekstrak Temulawak Curcuma',
                'category' => 'Jamu & Ekstrak',
                'unit' => 'botol',
                'purchase_price' => 28000,
                'selling_price' => 42000,
                'stock' => 8,
                'expiration_date' => now()->addMonths(9),
                'description' => 'Membantu menjaga fungsi organ hati dan menambah nafsu makan secara alami.',
            ],
            [
                'sku' => 'HBL-004',
                'name' => 'Sari Kurma Angkak Plus Madu & Propolis',
                'category' => 'Suplemen Herbal',
                'unit' => 'botol',
                'purchase_price' => 30000,
                'selling_price' => 48000,
                'stock' => 20,
                'expiration_date' => now()->addMonths(14),
                'description' => 'Membantu menaikkan trombosit dan mempercepat penyembuhan demam berdarah.',
            ],
            [
                'sku' => 'HBL-005',
                'name' => 'Teh Celup Daun Kelor Herbal Organik',
                'category' => 'Minuman Herbal',
                'unit' => 'box',
                'purchase_price' => 15000,
                'selling_price' => 25000,
                'stock' => 4,
                'expiration_date' => now()->addMonths(6),
                'description' => 'Superfood kaya vitamin dan mineral untuk menjaga kebugaran tubuh.',
            ],
            [
                'sku' => 'HBL-006',
                'name' => 'Minyak Zaitun Extra Virgin Tursina 250ml',
                'category' => 'Minyak Herbal',
                'unit' => 'botol',
                'purchase_price' => 35000,
                'selling_price' => 55000,
                'stock' => 12,
                'expiration_date' => now()->addMonths(10),
                'description' => 'Minyak perasan pertama buah zaitun murni, baik diminum maupun dioles.',
            ],
            [
                'sku' => 'HBL-007',
                'name' => 'Kapsul Ekstrak Pegagan (Centella Asiatica)',
                'category' => 'Jamu & Ekstrak',
                'unit' => 'botol',
                'purchase_price' => 32000,
                'selling_price' => 50000,
                'stock' => 18,
                'expiration_date' => now()->addMonths(11),
                'description' => 'Melancarkan peredaran darah ke otak dan meningkatkan daya ingat.',
            ],
            [
                'sku' => 'HBL-008',
                'name' => 'Wedang Uwuh Rempah Imogiri Asli',
                'category' => 'Minuman Herbal',
                'unit' => 'pack',
                'purchase_price' => 18000,
                'selling_price' => 30000,
                'stock' => 30,
                'expiration_date' => now()->addMonths(8),
                'description' => 'Campuran kayu secang, jahe, cengkeh, dan kapulaga untuk menghangatkan badan.',
            ],
            [
                'sku' => 'HBL-009',
                'name' => 'Kapsul Minyak Ikan Gabus Albumin',
                'category' => 'Suplemen Herbal',
                'unit' => 'botol',
                'purchase_price' => 48000,
                'selling_price' => 70000,
                'stock' => 7,
                'expiration_date' => now()->addMonths(15),
                'description' => 'Tinggi protein albumin untuk mempercepat pemulihan luka pasca operasi.',
            ],
            [
                'sku' => 'HBL-010',
                'name' => 'Garam Himalaya Murni Pink Salt 500g',
                'category' => 'Herbal & Alami',
                'unit' => 'pouch',
                'purchase_price' => 20000,
                'selling_price' => 35000,
                'stock' => 22,
                'expiration_date' => now()->addMonths(24),
                'description' => 'Garam mineral alami tanpa zat pemutih dan bebas yodium buatan.',
            ],
        ];

        foreach ($medicines as $med) {
            Medicine::updateOrCreate(['sku' => $med['sku']], $med);
        }
    }
}
