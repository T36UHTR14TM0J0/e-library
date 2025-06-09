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
        'ebook_id',
        'jenis',
        'status',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'denda',
        'catatan',
    ];

    protected $dates = ['tanggal_pinjam', 'tanggal_jatuh_tempo', 'tanggal_kembali'];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }


    // Method
    // public function hitungDenda()
    // {
    //     if ($this->status !== 'terlambat' || $this->tanggal_kembali) {
    //         return 0;
    //     }

    //     $hariTerlambat = now()->diffInDays($this->tanggal_jatuh_tempo);
    //     $dendaPerHari = config('perpustakaan.denda_per_hari', 5000); // Rp 5000/hari
    //     return $hariTerlambat * $dendaPerHari;
    // }

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
