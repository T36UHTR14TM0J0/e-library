<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peraturan extends Model
{
    use HasFactory;

    protected $table = 'peraturans';
    protected $fillable = ['urut', 'text', 'tipe'];

    const TIPE_UMUM = 1;
    const TIPE_RUANG_BACA = 2;

    public function scopeUmum($query)
    {
        return $query->where('tipe', self::TIPE_UMUM);
    }

    public function scopeRuangBaca($query)
    {
        return $query->where('tipe', self::TIPE_RUANG_BACA);
    }

    public function getTipeTextAttribute()
    {
        return $this->tipe == self::TIPE_UMUM ? 'Peraturan Umum' : 'Peraturan Ruang Baca';
    }
}