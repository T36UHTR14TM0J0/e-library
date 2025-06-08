<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Kategori;
use App\Models\Prodi;
use Illuminate\Http\Request;

class KatalogEbookControllers extends Controller
{
    public function index()
    {
        $query = Ebook::query()
            ->with(['kategori', 'prodi', 'pengunggah'])
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

        // Filter berdasarkan izin unduh
        if (request()->has('izin_unduh') && request('izin_unduh') != '') {
            $query->where('izin_unduh', request('izin_unduh'));
        }

        $ebooks = $query->paginate(12);

        $kategoris = Kategori::orderBy('nama')->get();
        $prodis = Prodi::orderBy('nama')->get();

        return view('katalog.ebook.index', compact('ebooks', 'kategoris', 'prodis'));
    }
}
