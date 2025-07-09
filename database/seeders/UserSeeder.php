<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan ini diimpor
use Illuminate\Support\Facades\Hash; // Untuk mengenkripsi password

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh menambahkan user admin
        User::create([
            'username' => 'admin', // Menggunakan 'name' sebagai username
            'email' => 'admin@digitalprinting.com',
            'password' => Hash::make('password'), // Ganti dengan password yang kuat
            'role' => 'admin', // Role untuk admin
        ]);

        // Contoh menambahkan user kasir
        User::create([
            'username' => 'kasir', // Menggunakan 'name' sebagai username
            'email' => 'kasir@digitalprinting.com',
            'password' => Hash::make('password'), // Ganti dengan password yang kuat
            'role' => 'kasir', // Role untuk kasir
        ]);

        // Anda bisa menambahkan lebih banyak user di sini
    }
}