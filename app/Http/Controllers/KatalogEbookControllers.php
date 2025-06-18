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
            ->with(['kategori', 'prodi', 'pengunggah', 'penerbit'])
            ->withCount(['readings as total_reads']);

        // Search filter
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%'.$search.'%')
                ->orWhere('penulis', 'like', '%'.$search.'%')
                ->orWhereHas('penerbit', function($q) use ($search) {
                    $q->where('nama', 'like', '%'.$search.'%');
                })
                ->orWhereHas('kategori', function($q) use ($search) {
                    $q->where('nama', 'like', '%'.$search.'%');
                })
                ->orWhereHas('prodi', function($q) use ($search) {
                    $q->where('nama', 'like', '%'.$search.'%');
                });
            });
        }

        // Sort options
        switch (request('sort')) {
                
            case 'sering_dibaca':
                $query->orderBy('total_reads', 'desc');
                break;
                
            case 'terbaru':
            default:
                $query->latest();
                break;
        }

        // Filter download permission
        if (request()->filled('izin_unduh')) {
            $query->where('izin_unduh', request('izin_unduh'));
        }

        $ebooks = $query->paginate(12);

        return view('katalog.ebook.index', compact('ebooks'));
    }

    public function show($id)
    {
        $ebook = Ebook::with(['kategori', 'prodi', 'pengunggah'])
                    ->findOrFail($id);
                    
        return view('katalog.ebook.show', compact('ebook'));
    }
}
