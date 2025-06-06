<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama_lengkap' => 'Admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'nama_lengkap' => 'Dosen',
            'email' => 'dosen@gmail.com',
            'username' => 'dosen',
            'password' => Hash::make('dosen123'),
            'role' => 'dosen',
            'nidn' => '1234567890',
        ]);

        User::create([
            'nama_lengkap' => 'Mahasiswa',
            'email' => 'mahasiswa@gmail.com',
            'username' => 'mahasiswa',
            'password' => Hash::make('mahasiswa123'),
            'role' => 'mahasiswa',
            'npm' => '1234567890',
        ]);
    }
}