<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = ['nama', 'deskripsi'];

    // Relasi
    public function buku()
    {
        return $this->hasMany(Buku::class);
    }

    public function ebook()
    {
        return $this->hasMany(Ebook::class);
    }

}
