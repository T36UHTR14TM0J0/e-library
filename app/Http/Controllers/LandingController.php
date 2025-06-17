<?php

namespace App\Http\Controllers;

use App\Models\JamLayanan;
use App\Models\Layanan;
use App\Models\Prosedur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingController extends Controller
{
    
    public function index()
    {
        return view('landing.home');
    }

    public function Prosedur()
    {
        $prosedurs = Prosedur::ordered();
        return view('landing.prosedur', compact('prosedurs'));
    }
    
    public function jamLayanan()
    {
        $hours = JamLayanan::orderByRaw("FIELD(hari, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")->get();
        return view('landing.jamLayanan', compact('hours'));
    }
    
    public function layanan()
    {
        $layanans = Layanan::all();
        return view('landing.layanan', compact('layanans'));
    }
    
    public function about()
    {
        $about = [
            'vision' => 'Menjadi pusat pengetahuan terdepan yang mendukung pembelajaran sepanjang hayat dan penelitian inovatif.',
            'mission' => [
                'Menyediakan akses pengetahuan yang mudah dan merata',
                'Mendukung pendidikan dan penelitian berkualitas',
                'Mengembangkan budaya literasi digital',
                'Menjadi mitra pembelajaran sepanjang hayat'
            ]
        ];
        
        return view('landing.about', compact('about'));
    }

}
