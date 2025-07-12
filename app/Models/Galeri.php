<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $fillable = ['urut', 'foto', 'judul', 'deskripsi', 'tipe'];
    
    protected $casts = [
        'tipe' => 'integer',
    ];
    
    public static function ordered()
    {
        return self::orderBy('urut')->get();
    }
}