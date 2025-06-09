<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    use HasFactory;
    protected $fillable = [
        'user_id',
        'buku_id',
        'status', // Menghapus ebook_id dan jenis karena tidak ada di migrasi
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'tanggal_setujui', // Menambahkan kolom tanggal_setujui
        'tanggal_batal', // Menambahkan kolom tanggal_batal
        'denda',
        'catatan_pinjam', // Mengganti catatan menjadi catatan_pinjam
        'catatan_kembali', // Menambahkan catatan_kembali
        'catatan_batal', // Menambahkan catatan_batal
        'disetujui_oleh', // Menambahkan kolom disetujui_oleh
        'dibatalkan_oleh', // Menambahkan kolom dibatalkan_oleh
    ];

    protected $dates = [
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'tanggal_setujui', // Menambahkan kolom tanggal_setujui
        'tanggal_batal', // Menambahkan kolom tanggal_batal
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_jatuh_tempo' => 'datetime',
        'tanggal_setujui' => 'datetime',
        'tanggal_pinjam' => 'datetime',
        'tanggal_batal' => 'datetime',
        'tanggal_kembali' => 'datetime'

    ];


    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function disetujui()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function dibatalkan_oleh()
    {
        return $this->belongsTo(User::class, 'dibatalkan_oleh');
    }

    // Method
    public function hitungDenda()
    {
        $hariTerlambat = now()->diffInDays($this->tanggal_jatuh_tempo);
        $dendaPerHari = 1000;// Rp 5000/hari
        return $hariTerlambat * $dendaPerHari;
    }

    public function isLate()
    {
        // Example logic - adjust according to your business rules
        return $this->tanggal_jatuh_tempo < now() && $this->status !== 'dikembalikan';
    }

    public function tandaiDikembalikan()
    {
        $this->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
            'denda' => $this->hitungDenda(),
        ]);
    }

}
