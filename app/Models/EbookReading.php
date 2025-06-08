<?php

// app/Models/EbookReading.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EbookReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ebook_id',
    ];

    /**
     * Hubungan ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hubungan ke model Ebook
     */
    public function ebook()
    {
        return $this->belongsTo(Ebook::class);
    }

    

       

    
}