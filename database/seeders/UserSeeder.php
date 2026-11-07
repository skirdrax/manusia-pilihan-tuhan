<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Kel 3',
                'email' => 'admin@kel3.test',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Kurir 1',
                'email' => 'kurir1@kel3.test',
                'password' => Hash::make('password'),
                'role' => 'kurir',
            ],
            [
                'name' => 'Kurir 2',
                'email' => 'kurir2@kel3.test',
                'password' => Hash::make('password'),
                'role' => 'kurir',
            ],
            [
                'name' => 'User Pelanggan',
                'email' => 'user@kel3.test',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}

