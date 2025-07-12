<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informasi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'informasis';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'judul',
        'icon',
        'min_kapasitas',
        'maks_kapasitas',
        'info',
        'warna',
        'waktu_buka',
        'waktu_tutup',
        'fasilitas'
    ];

    /**
     * Kolom yang harus di-cast ke tipe data tertentu.
     *
     * @var array
     */
    protected $casts = [
        'waktu_buka' => 'datetime:H:i:s',
        'waktu_tutup' => 'datetime:H:i:s',
        'fasilitas' => 'array' // Jika fasilitas disimpan sebagai JSON array
    ];

    /**
     * Aksesor untuk mendapatkan waktu buka dalam format yang lebih mudah dibaca.
     *
     * @return string
     */
    public function getWaktuBukaAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i');
    }

    /**
     * Aksesor untuk mendapatkan waktu tutup dalam format yang lebih mudah dibaca.
     *
     * @return string
     */
    public function getWaktuTutupAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i');
    }
}