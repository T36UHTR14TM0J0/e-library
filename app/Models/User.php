<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    
    protected $fillable = [
        'nama_lengkap', 
        'email',
        'username', 
        'password',
        'role',
        'npm',
        'nidn',
        'foto', 
        'prodi_id',
        'status_aktif'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDosen()
    {
        return $this->role === 'dosen';
    }

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    // Relasi ke model Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }


    /**
     * Hubungan ke reading history
     */
    public function ebookReadings()
    {
        return $this->hasMany(EbookReading::class);
    }

    
    /**
     * Hitung jumlah ebook yang sudah dibaca
     */
    public function getReadEbooksCountAttribute()
    {
        return $this->ebookReadings()
            ->select('ebook_id')
            ->distinct()
            ->count('ebook_id');
    }
}