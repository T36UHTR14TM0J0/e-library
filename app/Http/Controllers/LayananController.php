<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layanans = Layanan::when(request('nama'), function($query) {
                        $query->where('nama', 'like', '%'.request('nama').'%');
                    })
                    ->latest()
                    ->paginate(10);
        return view('pengaturan.layanan.index', compact('layanans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengaturan.layanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ], [
            'nama.required' => 'Nama layanan wajib diisi',
            'nama.string' => 'Nama layanan harus berupa teks',
            'nama.max' => 'Nama layanan maksimal 255 karakter',
            'icon.required' => 'Icon wajib diisi',
            'icon.string' => 'Icon harus berupa teks',
            'icon.max' => 'Icon maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'deskripsi.string' => 'Deskripsi harus berupa teks',
        ]);

        Layanan::create($request->all());

        return redirect()->route('layanan.index')
            ->with('success', 'Layanan berhasil ditambahkan');
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Layanan $layanan)
    // {
    //     return view('penga.show', compact('layanan'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Layanan $layanan)
    {
        return view('pengaturan.layanan.edit', compact('layanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Layanan $layanan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ], [
            'nama.required' => 'Nama layanan wajib diisi',
            'nama.string' => 'Nama layanan harus berupa teks',
            'nama.max' => 'Nama layanan maksimal 255 karakter',
            'icon.required' => 'Icon wajib diisi',
            'icon.string' => 'Icon harus berupa teks',
            'icon.max' => 'Icon maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'deskripsi.string' => 'Deskripsi harus berupa teks',
        ]);

        $layanan->update($request->all());

        return redirect()->route('layanan.index')
            ->with('success', 'Layanan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layanan $layanan)
    {
        $layanan->delete();

        return redirect()->route('layanan.index')
            ->with('success', 'Layanan berhasil dihapus');
    }
}