<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $fillable = ['kode', 'nama', 'deskripsi'];

    // Relasi
    public function pengguna()
    {
        return $this->hasMany(User::class, 'prodi_id');
    }

    public function bukuFisik()
    {
        return $this->hasMany(Buku::class, 'prodi_id');
    }

    // public function bukuDigital()
    // {
    //     return $this->hasMany(Ebook::class, 'prodi_id');
    // }
}

