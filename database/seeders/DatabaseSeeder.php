<?php

namespace Database\Seeders;

use App\Models\JamLayanan;
use App\Models\Layanan;
use App\Models\Prosedur;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Prosedur Kunjungan
        Prosedur::create([
            'urut' => 1,
            'judul' => 'Pendaftaran Anggota',
            'deskripsi' => 'Daftar sebagai anggota melalui website atau langsung di perpustakaan'
        ]);
        
        Prosedur::create([
            'urut' => 2,
            'judul' => 'Verifikasi Data',
            'deskripsi' => 'Verifikasi data diri dan email Anda'
        ]);
        
        // Jam Pelayanan
        JamLayanan::create([
            'hari' => 'Monday',
            'waktu_buka' => '08:00',
            'waktu_tutup' => '18:00'
        ]);
        
        // Layanan
        Layanan::create([
            'nama' => 'Peminjaman Buku',
            'icon' => 'fas fa-book',
            'deskripsi' => 'Maksimal 5 buku fisik untuk 14 hari'
        ]);
    }
}
