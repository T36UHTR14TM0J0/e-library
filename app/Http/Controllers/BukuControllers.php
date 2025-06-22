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
    
        $query = Buku::query()
            ->with(['kategori', 'prodi', 'penerbit']);

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

        $bukus = $query->paginate(12);


        return view('master_data.buku.index', compact('bukus'));
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
