<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerbit extends Model
{
    use HasFactory;

    protected $table = 'penerbits';
    
    protected $fillable = [
        'kode_penerbit',
        'nama',
        'alamat',
        'kota',
        'telepon',
        'email',
        'website',
    ];

    // Relasi dengan buku (jika ada)
    public function buku()
    {
        return $this->hasMany(Buku::class);
    }
}