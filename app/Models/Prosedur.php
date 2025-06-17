<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prosedur extends Model
{
    protected $fillable = ['kode', 'judul', 'deskripsi'];
    
    public static function ordered()
    {
        return self::orderBy('kode')->get();
    }
}
