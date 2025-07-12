<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Ebook;
use App\Models\JamLayanan;
use App\Models\Kategori;
use App\Models\Layanan;
use App\Models\Prodi;
use App\Models\Prosedur;
use App\Models\Galeri;
use App\Models\Informasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingController extends Controller
{
    /* 
    |--------------------------------------------------------------------------
    | LANDING PAGE METHODS
    |--------------------------------------------------------------------------
    */
    
    /**
     * Display the home page
     */
    public function index()
    {
         if (auth()->check()) {
            return redirect()->route('dashboard'); // atau route home/beranda Anda
        }
        return view('landing.home');
    }

    /**
     * Display the procedure page
     */
    public function Prosedur()
    {
        $prosedurs = Prosedur::ordered();
        return view('landing.prosedur', compact('prosedurs'));
    }
    
    /**
     * Display the service hours page
     */
    public function jamLayanan()
    {
        $hours = JamLayanan::orderByRaw("FIELD(hari, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")->get();
        return view('landing.jamLayanan', compact('hours'));
    }
    
    /**
     * Display the services page
     */
    public function layanan()
    {
        $layanans = Layanan::all();
        return view('landing.layanan', compact('layanans'));
    }
    
    /**
     * Display the about page
     */
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

    /* 
    |--------------------------------------------------------------------------
    | BOOK CATALOG METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Display physical books catalog with filtering options
     */
    public function viewBukuFisik(Request $request)
    {
        $search = $request->input('search');
        
        $filters = [
            'kategori_id' => $request->input('kategori_id'),
            'prodi_id' => $request->input('prodi_id'),
            'tahun_terbit' => $request->input('tahun_terbit'),
            'sort' => $request->input('sort', 'judul'),
        ];

        $query = Buku::with(['kategori', 'prodi', 'penerbit'])
            ->withCount(['peminjaman as total_peminjaman' => function($query) {
                $query->where(function($q) {
                    $q->where('status', 'dipinjam')
                      ->orWhere('status', 'dikembalikan');
                })
                ->whereNotNull('tanggal_setujui');
            }]);

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%'.$search.'%')
                  ->orWhere('penulis', 'like', '%'.$search.'%')
                  ->orWhere('tahun_terbit', 'like', '%'.$search.'%')
                  ->orWhereHas('kategori', function($q) use ($search) {
                      $q->where('nama', 'like', '%'.$search.'%');
                  })
                  ->orWhereHas('prodi', function($q) use ($search) {
                      $q->where('nama', 'like', '%'.$search.'%');
                  })
                  ->orWhereHas('penerbit', function($q) use ($search) {
                      $q->where('nama', 'like', '%'.$search.'%');
                  });
            });
        }

        // Apply filters
        if ($filters['kategori_id']) {
            $query->where('kategori_id', $filters['kategori_id']);
        }

        if ($filters['prodi_id']) {
            $query->where('prodi_id', $filters['prodi_id']);
        }

        if ($filters['tahun_terbit']) {
            $query->where('tahun_terbit', $filters['tahun_terbit']);
        }

        // Sorting
        switch ($filters['sort']) {
            case 'popular':
                $query->orderBy('total_peminjaman', 'desc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('judul');
        }

        $bukus = $query->paginate(10)
            ->withQueryString();
            
        $tahunTerbit = Buku::select('tahun_terbit')
            ->distinct()
            ->orderBy('tahun_terbit', 'desc')
            ->pluck('tahun_terbit');
            
        $kategoris = Kategori::orderBy('nama')->get();
        $prodis = Prodi::orderBy('nama')->get();
        
        return view('landing.katalog.buku', compact('bukus', 'tahunTerbit', 'kategoris', 'prodis', 'filters', 'search'));
    }

    /**
     * Display ebooks catalog with filtering options
     */
    public function viewEbook(Request $request)
    {
        $search = $request->input('search');
        
        $filters = [
            'kategori_id' => $request->input('kategori_id'),
            'prodi_id' => $request->input('prodi_id'),
            'sort' => $request->input('sort', 'judul'),
        ];

        $query = Ebook::with(['kategori', 'prodi', 'penerbit'])
            ->withCount(['readings as total_dibaca']);

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%'.$search.'%')
                  ->orWhere('penulis', 'like', '%'.$search.'%')
                  ->orWhereHas('kategori', function($q) use ($search) {
                      $q->where('nama', 'like', '%'.$search.'%');
                  })
                  ->orWhereHas('prodi', function($q) use ($search) {
                      $q->where('nama', 'like', '%'.$search.'%');
                  });
            });
        }

        // Apply filters
        if ($filters['kategori_id']) {
            $query->where('kategori_id', $filters['kategori_id']);
        }

        if ($filters['prodi_id']) {
            $query->where('prodi_id', $filters['prodi_id']);
        }

        // Sorting
        switch ($filters['sort']) {
            case 'popular':
                $query->orderBy('total_dibaca', 'desc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('judul');
        }

        $ebooks = $query->paginate(10)
            ->withQueryString();
            
        $kategoris = Kategori::orderBy('nama')->get();
        $prodis = Prodi::orderBy('nama')->get();
        
        return view('landing.katalog.ebook', compact('ebooks', 'kategoris', 'prodis', 'filters', 'search'));
    }

    /* 
    |--------------------------------------------------------------------------
    | DETAIL PAGES METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Display ebook detail page
     */
    public function detailEbook($id)
    {
        $ebook = Ebook::with(['kategori', 'prodi', 'pengunggah'])
                    ->findOrFail($id);
                    
        return view('landing.katalog.detail_ebook', compact('ebook'));
    }

    /**
     * Display physical book detail page
     */
    public function detailBuku($id)
    {
         $buku = Buku::with(['kategori', 'prodi'])
                    ->findOrFail($id);
        return view('landing.katalog.detail_buku', compact('buku'));
    }

    public function galeri()
    {
         $galeris = Galeri::where('tipe', '1')->paginate(6);
         $aktivitass = Galeri::where('tipe', '2')->paginate(6);
        return view('landing.galeri', compact('galeris', 'aktivitass'));
    }

    public function informasi()
    {
        $informasi = Informasi::all();
        return view('landing.informasi', compact('informasi'));
    }
}