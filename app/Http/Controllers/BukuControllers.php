<?php

namespace App\Http\Controllers;

use App\Http\Requests\BukuRequest;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Penerbit;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuControllers extends Controller
{
    public function index()
    {
        $bukus = Buku::with(['kategori', 'prodi','penerbit'])
            ->when(request('judul'), function($query) {
                $query->where('judul', 'like', '%'.request('judul').'%');
            })
            ->when(request('penerbit_id'), function($query) {
                $query->where('penerbit_id', 'like', '%'.request('penerbit_id').'%');
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
        $penerbits  = Penerbit::all();

        return view('master_data.buku.index', compact('bukus', 'kategoris', 'prodis','penerbits'));
    }

    public function create()
    {
        $kategoris  = Kategori::all();
        $prodis     = Prodi::all();
        $penerbits  = Penerbit::all();
        return view('master_data.buku.create',compact('kategoris','prodis','penerbits'));
    }

    // Pada method store dan update
    public function store(BukuRequest $request)
    {
        $validated = $request->validated();
        
               // Handle cover image upload
        if ($request->hasFile('gambar_sampul')) {
            $image       = $request->file('gambar_sampul');
            $imageName   = time() . '_' . $image->getClientOriginalName();
            $image->move(storage_path('app/public/buku-covers'), $imageName);
            $validated['gambar_sampul'] = 'buku-covers/' . $imageName;
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
        return view('master_data.buku.show', compact('buku'));
    }


    public function edit(Buku $buku)
    {
        $kategoris  = Kategori::all();
        $prodis     = Prodi::all();
        $penerbits  = Penerbit::all();
        
        return view('master_data.buku.edit', [
            'buku'      => $buku,
            'kategoris' => $kategoris,
            'prodis'    => $prodis,
            'penerbits' => $penerbits
        ]);
    }

    
    public function update(BukuRequest $request, Buku $buku)
    {
        $validated = $request->validated();
        
        // Handle gambar sampul upload
        if ($request->hasFile('gambar_sampul')) {
            // Hapus gambar lama jika ada
            if ($buku->gambar_sampul) {
                Storage::delete('public/' . $buku->gambar_sampul);
            }
            
            // Simpan gambar baru
            $image      = $request->file('gambar_sampul');
            $imageName  = 'cover_' . $buku->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath  = $image->storeAs('buku-covers', $imageName, 'public');
            $validated['gambar_sampul'] = $imagePath;
            
        } elseif ($request->boolean('hapus_gambar')) {
            // Hapus gambar jika checkbox dicentang
            if ($buku->gambar_sampul) {
                Storage::delete('public/' . $buku->gambar_sampul);
            }
            $validated['gambar_sampul'] = null;
        }
        // Jika tidak ada perubahan gambar, biarkan nilai yang ada
        
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
