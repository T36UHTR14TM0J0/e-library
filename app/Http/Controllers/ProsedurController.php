<?php

namespace App\Http\Controllers;

use App\Models\Prosedur;
use Illuminate\Http\Request;

class ProsedurController extends Controller
{
    public function index()
    {
        $prosedurs = Prosedur::when(request('judul'), function($query) {
                        $query->where('judul', 'like', '%'.request('judul').'%');
                    })
                    ->orderBy('urut', 'asc')
                    ->paginate(10);
        
        return view('pengaturan.prosedur.index', compact('prosedurs'));
    }

    public function create()
    {
        return view('pengaturan.prosedur.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'urut' => 'required|numeric|unique:prosedurs,urut',
            'judul' => 'required|max:255',
            'deskripsi' => 'required',
        ], [
            'urut.required' => 'Kolom urutan wajib diisi',
            'urut.numeric' => 'Urutan harus berupa angka',
            'urut.unique' => 'Nomor urutan sudah digunakan',
            'judul.required' => 'Kolom judul wajib diisi',
            'judul.max' => 'Judul maksimal 255 karakter',
            'deskripsi.required' => 'Kolom deskripsi wajib diisi',
        ]);

        Prosedur::create($validated);

        return redirect()->route('prosedur.index')
                        ->with('success', 'Prosedur berhasil ditambahkan');
    }

    public function edit(Prosedur $prosedur)
    {
        return view('pengaturan.prosedur.edit', compact('prosedur'));
    }

    public function update(Request $request, Prosedur $prosedur)
    {
        $validated = $request->validate([
            'urut' => 'required|numeric|unique:prosedurs,urut,'.$prosedur->id,
            'judul' => 'required|max:255',
            'deskripsi' => 'required',
        ], [
            'urut.required' => 'Kolom urutan wajib diisi',
            'urut.numeric' => 'Urutan harus berupa angka',
            'urut.unique' => 'Nomor urutan sudah digunakan',
            'judul.required' => 'Kolom judul wajib diisi',
            'judul.max' => 'Judul maksimal 255 karakter',
            'deskripsi.required' => 'Kolom deskripsi wajib diisi',
        ]);

        $prosedur->update($validated);

        return redirect()->route('prosedur.index')
                        ->with('success', 'Prosedur berhasil diperbarui');
    }

    public function destroy(Prosedur $prosedur)
    {
        $prosedur->delete();

        return redirect()->route('prosedur.index')
                        ->with('success', 'Prosedur berhasil dihapus');
    }
}