<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'penulis',
        'kategori_id',
        'prodi_id',
        'uploaded_by',
        'file_url',
        'gambar_sampul',
        'deskripsi',
        'izin_unduh',
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

    public function pengunggah()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // public function transaksi()
    // {
    //     return $this->hasMany(Transaction::class);
    // }

    // Scope
    // public function scopePublik($query)
    // {
    //     return $query->where('publik', true);
    // }

    // public function scopeUntukProdi($query, $prodiId)
    // {
    //     return $query->where('prodi_id', $prodiId)
    //         ->orWhere('publik', true);
    // }
}
