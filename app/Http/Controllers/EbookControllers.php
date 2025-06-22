<?php

namespace App\Http\Controllers;

use App\Http\Requests\EbookRequest;
use App\Models\Ebook;
use App\Models\Kategori;
use App\Models\Penerbit;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookControllers extends Controller
{
    public function index()
    {
        // $ebooks = Ebook::with(['kategori', 'prodi', 'pengunggah','penerbit'])
        //     ->when(request('judul'), function($query) {
        //         $query->where('judul', 'like', '%'.request('judul').'%');
        //     })
        //     ->when(request('penerbit_id'), function($query) {
        //         $query->where('penerbit_id', 'like', '%'.request('penerbit_id').'%');
        //     })
        //     ->when(request('kategori_id'), function($query) {
        //         $query->where('kategori_id', request('kategori_id'));
        //     })
        //     ->when(request('prodi_id'), function($query) {
        //         $query->where('prodi_id', request('prodi_id'));
        //     })
        //     ->when(request()->has('izin_unduh'), function($query) {
        //         $query->where('izin_unduh', request('izin_unduh'));
        //     })
        //     ->orderBy(request('sort_by', 'created_at'), request('sort_direction', 'desc'))
        //     ->paginate(request('per_page', 10));

        // $kategoris  = Kategori::all();
        // $prodis     = Prodi::all();
        // $penerbits  = Prodi::all();

        $query = Ebook::query()
            ->with(['kategori', 'prodi', 'pengunggah', 'penerbit']);

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


        // Filter download permission
        if (request()->filled('izin_unduh')) {
            $query->where('izin_unduh', request('izin_unduh'));
        }

        $ebooks = $query->paginate(12);

        return view('master_data.ebook.index', compact('ebooks'));
    }

    public function create()
    {
        $kategoris  = Kategori::all();
        $prodis     = Prodi::all();
        $penerbits  = Penerbit::all();
        return view('master_data.ebook.create',compact('kategoris','prodis','penerbits'));
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
            $image       = $request->file('gambar_sampul');
            $imageName   = time() . '_' . $image->getClientOriginalName();
            $image->move(storage_path('app/public/ebooks'), $imageName);
            $validated['gambar_sampul'] = 'ebooks/' . $imageName;
        }

        // Set uploaded_by to current user
        $validated['uploaded_by'] = auth()->id();
        $validated['izin_unduh']  = $request->has('izin_unduh') ? true : false;

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
        return view('master_data.ebook.show', compact('ebook'));
    }


    public function edit(Ebook $ebook)
    {
        $kategoris  = Kategori::all();
        $prodis     = Prodi::all();
        $penerbits  = Penerbit::all();
        
        return view('master_data.ebook.edit', [
            'ebook'       => $ebook,
            'kategoris'   => $kategoris,
            'prodis'      => $prodis,
            'penerbits'   => $penerbits
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
