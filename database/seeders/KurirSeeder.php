<?php

namespace Database\Seeders;

use App\Models\Kurir;
use Illuminate\Database\Seeder;

class KurirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kurirs = [
            [
                'id' => 1,
                'nama' => 'Budi Santoso',
                'telepon' => '081234567890',
            ],
            [
                'id' => 2,
                'nama' => 'Andi Wijaya',
                'telepon' => '081298765432',
            ],
        ];

        foreach ($kurirs as $data) {
            Kurir::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
        }
    }
}

