<?php

namespace App\Http\Controllers;

use App\Http\Requests\BukuRequest;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuControllers extends Controller
{
    public function index()
    {
        $bukus = Buku::with(['kategori', 'prodi'])
            ->when(request('judul'), function($query) {
                $query->where('judul', 'like', '%'.request('judul').'%');
            })
            ->when(request('penulis'), function($query) {
                $query->where('penulis', 'like', '%'.request('penulis').'%');
            })
            ->when(request('kategori_id'), function($query) {
                $query->where('kategori_id', request('kategori_id'));
            })
            ->when(request('prodi_id'), function($query) {
                $query->where('prodi_id', request('prodi_id'));
            })
            ->orderBy(request('sort_by', 'created_at'), request('sort_direction', 'desc'))
            ->paginate(request('per_page', 10));

        $kategoris  = Kategori::all();
        $prodis     = Prodi::all();

        return view('buku.index', compact('bukus', 'kategoris', 'prodis'));
    }

    public function create()
    {
        $kategoris  = Kategori::all();
        $prodis     = Prodi::all();
        return view('buku.create',compact('kategoris','prodis'));
    }

    // Pada method store dan update
    public function store(BukuRequest $request)
    {
        $validated = $request->validated();
        
        // Handle file upload
        if ($request->hasFile('gambar_sampul')) {
            $imagePath = $request->file('gambar_sampul')->store('public/buku_covers');
            $validated['gambar_sampul'] = str_replace('public/', '', $imagePath);
        }

        try {
            Buku::create($validated);
            return redirect()->route('buku.index')
                ->with('success', 'Buku berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan buku. Error: '.$e->getMessage());
        }
    }

    public function show(Buku $buku)
    {
        return view('buku.show', compact('buku'));
    }


    public function edit(Buku $buku)
    {
        $kategoris = Kategori::all();
        $prodis = Prodi::all();
        
        return view('buku.edit', [
            'buku' => $buku,
            'kategoris' => $kategoris,
            'prodis' => $prodis
        ]);
    }

    
    public function update(BukuRequest $request, Buku $buku)
    {
        $validated = $request->validated();
        // Handle image upload/deletion
        if ($request->hasFile('gambar_sampul')) {
            // Delete old image if exists
            if ($buku->gambar_sampul) {
                Storage::delete('public/' . $buku->gambar_sampul);
            }
            
            // Store new image
            $path = $request->file('gambar_sampul')->store('buku-covers', 'public');
            $validated['gambar_sampul'] = $path;
        } elseif ($request->has('hapus_gambar')) {
            // Delete current image if checkbox is checked
            if ($buku->gambar_sampul) {
                Storage::delete('public/' . $buku->gambar_sampul);
                $validated['gambar_sampul'] = null;
            }
        }

        $buku->update($validated);

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil diperbarui');
    }


    
    public function destroy($id)
    {
        try {
            $buku = Buku::findOrFail($id);
            $buku->delete();

            return redirect()->route('buku.index')
                   ->with('success', 'buku berhasil dihapus');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('buku.index')
                   ->with('error', 'buku tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('buku.index')
                   ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }
}
