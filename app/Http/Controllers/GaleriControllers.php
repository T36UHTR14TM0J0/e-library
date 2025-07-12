<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class GaleriControllers extends Controller
{
    public function index()
    {
       $galeris = Galeri::where('tipe', 1)
        ->when(request('judul'), function($query) {
            $query->where('judul', 'like', '%'.request('judul').'%');
        })
        ->orderBy('urut', 'asc')
        ->paginate(10);
        return view('galeri.index', compact('galeris'));
    }

    public function create()
    {
        return view('galeri.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'urut' => 'required|integer|min:1|unique:galeris,urut,NULL,id,tipe,1',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                // 'tipe' dihapus dari validasi karena akan di-set otomatis
            ], [
                'urut.required' => 'Kolom urutan wajib diisi',
                'urut.integer' => 'Urutan harus berupa bilangan bulat',
                'urut.unique' => 'Nomor urutan sudah digunakan',
                'urut.min' => 'Urutan minimal 1',
                'judul.required' => 'Kolom judul wajib diisi',
                'judul.max' => 'Judul maksimal 255 karakter',
                'deskripsi.required' => 'Kolom deskripsi wajib diisi',
                'foto.required' => 'Foto wajib diunggah',
                'foto.image' => 'File harus berupa gambar',
                'foto.mimes' => 'Format gambar yang didukung: jpeg, png, jpg, gif',
                'foto.max' => 'Ukuran gambar maksimal 2MB',
            ]);

            // Handle file upload
            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
                
                $path = $image->storeAs('foto', $imageName, 'public');
                
                if (!$path) {
                    throw new \Exception('Gagal menyimpan gambar');
                }
                
                $validated['foto'] = $path;
            }

            // Set tipe otomatis ke 1
            $validated['tipe'] = 1;

            Galeri::create($validated);

            return redirect()->route('galeri.index')
                            ->with('success', 'Galeri berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                            ->withErrors($e->validator)
                            ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                            ->withInput();
        }
    }

    public function edit(Galeri $galeri)
    {
        return view('galeri.edit', compact('galeri'));
    }

   public function update(Request $request, Galeri $galeri)
    {
        try {
            $rules = [
                'urut' => 'required|integer|min:1|unique:galeris,urut,'.$galeri->id.',id,tipe,1',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ];

            $messages = [
                'urut.required' => 'Kolom urutan wajib diisi',
                'urut.integer' => 'Urutan harus berupa bilangan bulat',
                'urut.unique' => 'Nomor urutan sudah digunakan',
                'urut.min' => 'Urutan minimal 1',
                'judul.required' => 'Kolom judul wajib diisi',
                'judul.max' => 'Judul maksimal 255 karakter',
                'deskripsi.required' => 'Kolom deskripsi wajib diisi',
                'foto.image' => 'File harus berupa gambar',
                'foto.mimes' => 'Format gambar yang didukung: jpeg, png, jpg, gif, webp',
                'foto.max' => 'Ukuran gambar maksimal 2MB',
            ];

            $validated = $request->validate($rules, $messages);

            // Handle photo update
            if ($request->hasFile('foto')) {
                // Delete old photo if exists
                if ($galeri->foto) {
                    Storage::disk('public')->delete($galeri->foto);
                }

                // Upload new photo
                $image = $request->file('foto');
                $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
                
                $path = $image->storeAs('galeri', $imageName, 'public');
                
                if (!$path) {
                    throw new \Exception('Gagal menyimpan gambar');
                }
                
                $validated['foto'] = $path;
            } else {
                // Keep existing photo if no new photo is uploaded
                unset($validated['foto']);
            }

            $galeri->update($validated);

            return redirect()->route('galeri.index')
                            ->with('success', 'Data galeri berhasil diperbarui');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                            ->withErrors($e->validator)
                            ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                            ->withInput();
        }
    }

    public function show(Galeri $galeri)
    {
        return view('galeri.detail', compact('galeri'));
    }


    public function destroy(Galeri $galeri)
    {
        $galeri->delete();

        return redirect()->route('galeri.index')
                        ->with('success', 'Galeri berhasil dihapus');
    }
}