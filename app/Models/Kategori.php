<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = ['nama', 'deskripsi'];

    // Relasi
    public function bukuFisik()
    {
        return $this->hasMany(Buku::class);
    }

    // public function bukuDigital()
    // {
    //     return $this->hasMany(Eb::class);
    // }

}
