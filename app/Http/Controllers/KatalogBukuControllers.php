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
            ->with(['kategori', 'prodi'])
            ->latest();

        // Filter berdasarkan judul
        if (request()->has('judul') && request('judul') != '') {
            $query->where('judul', 'like', '%' . request('judul') . '%');
        }

        // Filter berdasarkan penulis
        if (request()->has('penulis') && request('penulis') != '') {
            $query->where('penulis', 'like', '%' . request('penulis') . '%');
        }

        // Filter berdasarkan kategori
        if (request()->has('kategori_id') && request('kategori_id') != '') {
            $query->where('kategori_id', request('kategori_id'));
        }

        // Filter berdasarkan prodi
        if (request()->has('prodi_id') && request('prodi_id') != '') {
            $query->where('prodi_id', request('prodi_id'));
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
