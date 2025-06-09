<?php

namespace App\Http\Controllers;

use App\Http\Requests\EbookRequest;
use App\Models\Ebook;
use App\Models\Kategori;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookControllers extends Controller
{
    public function index()
    {
        $ebooks = Ebook::with(['kategori', 'prodi', 'pengunggah'])
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
            ->when(request()->has('izin_unduh'), function($query) {
                $query->where('izin_unduh', request('izin_unduh'));
            })
            ->orderBy(request('sort_by', 'created_at'), request('sort_direction', 'desc'))
            ->paginate(request('per_page', 10));

        $kategoris  = Kategori::all();
        $prodis     = Prodi::all();

        return view('ebook.index', compact('ebooks', 'kategoris', 'prodis'));
    }

    public function create()
    {
        $kategoris  = Kategori::all();
        $prodis     = Prodi::all();
        return view('ebook.create',compact('kategoris','prodis'));
    }

    // Pada method store dan update
    public function store(EbookRequest $request)
    {
        $validated = $request->validated();

        // Handle upload file
        if ($request->hasFile('file_url')) {
            $file       = $request->file('file_url');
            $fileName   = time() . '_' . $file->getClientOriginalName();
            $file->move(storage_path('app/public/ebooks'), $fileName);
            $validated['file_url'] = 'ebooks/' . $fileName;
        }

        // Handle cover image upload
        if ($request->hasFile('gambar_sampul')) {
            $image       = $request->file('file_url');
            $imageName   = time() . '_' . $file->getClientOriginalName();
            $image->move(storage_path('app/public/ebooks'), $imageName);
            $validated['file_url'] = 'ebooks/' . $imageName;
        }

        // Set uploaded_by to current user
        $validated['uploaded_by'] = auth()->id();
        $validated['izin_unduh'] = $request->has('izin_unduh') ? true : false;

        try {
            Ebook::create($validated);
            return redirect()->route('ebook.index')
                ->with('success', 'Ebook berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Delete uploaded files if creation fails
            if (isset($validated['file_url'])) {
                Storage::delete('public/' . $validated['file_url']);
            }
            if (isset($validated['gambar_sampul'])) {
                Storage::delete('public/' . $validated['gambar_sampul']);
            }
            
            return back()->withInput()
                ->with('error', 'Gagal menambahkan ebook. Error: '.$e->getMessage());
        }
    }

    public function show(Ebook $ebook)
    {
        return view('ebook.show', compact('ebook'));
    }


    public function edit(Ebook $ebook)
    {
        $kategoris  = Kategori::all();
        $prodis     = Prodi::all();
        
        return view('ebook.edit', [
            'ebook'       => $ebook,
            'kategoris'   => $kategoris,
            'prodis'      => $prodis
        ]);
    }

    
    public function update(EbookRequest $request, Ebook $ebook)
    {
        $validated = $request->validated();
        // Handle file upload
        if ($request->hasFile('file_url')) {
            // Hapus file lama jika ada
            if ($ebook->file_url) {
                Storage::delete('public/' . $ebook->file_url);
            }
            
            // Simpan file baru
             $file       = $request->file('file_url');
            $fileName   = time() . '_' . $file->getClientOriginalName();
            $file->move(storage_path('app/public/ebooks'), $fileName);
            $validated['file_url'] = 'ebooks/' . $fileName;
        } elseif ($request->has('hapus_file') && $request->hapus_file) {
            // Hapus file jika checkbox dicentang
            Storage::delete('public/' . $ebook->file_url);
            $validated['file_url'] = null;
        } else {
            // Pertahankan file yang ada
            $validated['file_url'] = $ebook->file_url;
        }

        // Handle gambar sampul upload
        if ($request->hasFile('gambar_sampul')) {
            // Hapus gambar lama jika ada
            if ($ebook->gambar_sampul) {
                Storage::delete('public/' . $ebook->gambar_sampul);
            }
            
            $image       = $request->file('gambar_sampul');
            $imageName   = time() . '_' . $image->getClientOriginalName();
            $image->move(storage_path('app/public/ebooks'), $imageName);
            $validated['gambar_sampul'] = 'ebooks/' . $imageName;
        } elseif ($request->has('hapus_gambar') && $request->hapus_gambar) {
            // Hapus gambar jika checkbox dicentang
            Storage::delete('public/' . $ebook->gambar_sampul);
            $validated['gambar_sampul'] = null;
        } else {
            // Pertahankan gambar yang ada
            $validated['gambar_sampul'] = $ebook->gambar_sampul;
        }

        // Handle izin unduh
        $validated['izin_unduh'] = $request->has('izin_unduh') ? 1 : 0;

        // Update data ebook
        $ebook->update($validated);

        return redirect()->route('ebook.index')
            ->with('success', 'Ebook berhasil diperbarui');
    }


    
    public function destroy($id)
    {
        try {
            $ebook = Ebook::findOrFail($id);
            
            // Hapus file eBook jika ada
            if (!empty($ebook->file_url)) {
                $filePath = public_path('storage/' . $ebook->file_url);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Hapus gambar sampul jika ada
            if (!empty($ebook->gambar_sampul)) {
                $coverPath = public_path('storage/' . $ebook->gambar_sampul);
                if (file_exists($coverPath)) {
                    unlink($coverPath);
                }
            }
            
            // Hapus record dari database
            $ebook->delete();

            return redirect()->route('ebook.index')
                ->with('success', 'Ebook berhasil dihapus');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('ebook.index')
                ->with('error', 'Ebook tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('ebook.index')
                ->with('error', 'Gagal menghapus ebook: ' . $e->getMessage());
        }
    }
}
