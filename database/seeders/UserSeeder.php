<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan ini diimpor
use Illuminate\Support\Facades\Hash; // Untuk mengenkripsi password

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin', 
            'email' => 'admin@digitalprinting.com',
            'password' => Hash::make('password'), 
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'kasir', 
            'email' => 'kasir@digitalprinting.com',
            'password' => Hash::make('password'), 
            'role' => 'kasir', 
        ]);
    }
}