<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'penulis',
        'isbn',
        'penerbit',
        'tahun_terbit',
        'jumlah',
        'kategori_id',
        'penerbit_id',
        'prodi_id',
        'deskripsi',
        'gambar_sampul',
    ];

    // Relasi
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function penerbit()
    {
        return $this->belongsTo(Penerbit::class, 'penerbit_id');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Method
    public function jumlahTersedia()
    {
        return $this->jumlah;
    }

    public function jumlah_stok()
    {
        return $this->jumlah + $this->dipinjam();
    }
    
    public function dipinjam()
    {
        $dipinjam = $this->peminjaman()
            ->where('status', 'dipinjam')
            ->count();
        return $dipinjam;
    }

    public function tersedia()
    {
        return $this->jumlahTersedia() > 0;
    }
}
