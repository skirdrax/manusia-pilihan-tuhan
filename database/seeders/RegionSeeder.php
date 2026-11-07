<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            [
                'gadm_id' => 'IDN.1.1_1',
                'name' => 'DKI Jakarta',
                'geometry' => '{"type":"Point","coordinates":[106.8456,-6.2088]}',
            ],
            [
                'gadm_id' => 'IDN.2.1_1',
                'name' => 'Jawa Barat (Bandung)',
                'geometry' => '{"type":"Point","coordinates":[107.6191,-6.9175]}',
            ],
            [
                'gadm_id' => 'IDN.3.1_1',
                'name' => 'Jawa Tengah (Semarang & Solo)',
                'geometry' => '{"type":"Point","coordinates":[110.4203,-6.9932]}',
            ],
            [
                'gadm_id' => 'IDN.4.1_1',
                'name' => 'DI Yogyakarta',
                'geometry' => '{"type":"Point","coordinates":[110.3695,-7.7956]}',
            ],
            [
                'gadm_id' => 'IDN.5.1_1',
                'name' => 'Jawa Timur (Surabaya & Malang)',
                'geometry' => '{"type":"Point","coordinates":[112.7521,-7.2575]}',
            ],
            [
                'gadm_id' => 'IDN.6.1_1',
                'name' => 'Bali (Denpasar & Badung)',
                'geometry' => '{"type":"Point","coordinates":[115.1768,-8.7231]}',
            ],
            [
                'gadm_id' => 'IDN.7.1_1',
                'name' => 'Sumatera Utara (Medan)',
                'geometry' => '{"type":"Point","coordinates":[98.6722,3.5952]}',
            ],
            [
                'gadm_id' => 'IDN.8.1_1',
                'name' => 'Sulawesi Selatan (Makassar)',
                'geometry' => '{"type":"Point","coordinates":[119.4327,-5.1477]}',
            ],
        ];

        foreach ($regions as $reg) {
            Region::updateOrCreate(['gadm_id' => $reg['gadm_id']], $reg);
        }
    }
}

