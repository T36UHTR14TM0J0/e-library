<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Prodi;
use Illuminate\Http\Request;

class KatalogBukuControllers extends Controller
{
   public function index()
{
    $query = Buku::query()
        ->with(['kategori', 'prodi', 'penerbit'])
        ->withCount(['peminjaman as peminjaman_count']); // Hitung jumlah peminjaman

    // Filter pencarian
    if (request()->has('search') && request('search') != '') {
        $searchTerm = request('search');
        $query->where(function($q) use ($searchTerm) {
            $q->where('judul', 'like', '%' . $searchTerm . '%')
              ->orWhere('penulis', 'like', '%' . $searchTerm . '%')
              ->orWhereHas('penerbit', function($q) use ($searchTerm) {
                  $q->where('nama', 'like', '%'.$searchTerm.'%');
              })
              ->orWhereHas('kategori', function($q) use ($searchTerm) {
                  $q->where('nama', 'like', '%'.$searchTerm.'%');
              })
              ->orWhereHas('prodi', function($q) use ($searchTerm) {
                  $q->where('nama', 'like', '%'.$searchTerm.'%');
              });
        });
    }

    // Sorting
    if (request()->has('sort')) {
        switch (request('sort')) {
            case 'favorit': // Favorit = sering dipinjam
            case 'sering_dipinjam':
                $query->orderBy('peminjaman_count', 'desc');
                break;
            case 'terbaru':
                $query->latest();
                break;
            default:
                $query->latest();
        }
    } else {
        $query->latest();
    }

    $bukus = $query->paginate(12);

    $kategoris = Kategori::orderBy('nama')->get();
    $prodis = Prodi::orderBy('nama')->get();

    return view('katalog.buku.index', compact('bukus', 'kategoris', 'prodis'));
}

    public function show($id)
    {
         $buku = Buku::with(['kategori', 'prodi'])
                    ->findOrFail($id);
        return view('katalog.buku.show', compact('buku'));
    }
}
