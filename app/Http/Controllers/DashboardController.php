<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Ebook;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard', [
        'totalBooks' => Buku::count(),
        'totalEbook' => Ebook::count(),
        'totalUsers' => User::count(),
        'peminjamanBuku' => Peminjaman::where('status','dipinjam')->count(),
        'overdueBooks' => Peminjaman::with(['buku', 'user'])
            ->where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', now())
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get()
    ]);
    }
}
