<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Ebook;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard', [
        'totalBooks' => Buku::count(),
        'totalEbook' => Ebook::count(),
        'totalUsers' => User::count(),
        // 'borrowedBooks' => Borrow::whereNull('return_date')->count(),
        // 'overdueBooks' => Borrow::with(['book', 'user'])
        //     ->whereNull('return_date')
        //     ->where('due_date', '<', now())
        //     ->get()
    ]);
    }
}
