<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamLayanan extends Model
{
    protected $fillable = ['hari', 'waktu_buka', 'waktu_tutup', 'catatan'];
    
    protected $casts = [
        'waktu_buka' => 'datetime:H:i',
        'waktu_tutup' => 'datetime:H:i'
    ];
}
