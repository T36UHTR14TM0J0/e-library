<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'penerbit_id',
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

    public function pengunggah()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }


    // Accessor untuk memeriksa tipe file
    public function getFileTypeAttribute()
    {
        if ($this->file_url) {
            return strtolower(pathinfo($this->file_url, PATHINFO_EXTENSION));
        }
        return null;
    }

    // Accessor untuk memeriksa apakah file ada
    public function getFileExistsAttribute()
    {
        if ($this->file_url) {
            // Cek apakah URL eksternal atau lokal
            if (filter_var($this->file_url, FILTER_VALIDATE_URL)) {
                return true; // Untuk URL eksternal, kita asumsikan ada
            }
            return Storage::disk('public')->exists($this->file_url);
        }
        return false;
    }

    /**
     * Hubungan ke reading history
     */
    public function readings()
    {
        return $this->hasMany(EbookReading::class);
    }


    /**
     * Relationship with users through readings
     */
    public function readers()
    {
        return $this->belongsToMany(User::class, 'ebook_readings')
                   ->withTimestamps();
    }


    public function getTotalReadsAttribute()
    {
        return $this->readings()->count();
    }

        

    // public function transaksi()
    // {
    //     return $this->hasMany(Transaction::class);
    // }


    // public function scopeUntukProdi($query, $prodiId)
    // {
    //     return $query->where('prodi_id', $prodiId)
    //         ->orWhere('publik', true);
    // }
}
