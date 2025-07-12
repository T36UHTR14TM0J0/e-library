<?php

namespace App\Http\Controllers;

use App\Models\Peraturan;
use Illuminate\Http\Request;

class PeraturanController extends Controller
{
    public function index()
    {
        $peraturanUmum = Peraturan::umum()->orderBy('urut')->get();
        $peraturanRuangBaca = Peraturan::ruangBaca()->orderBy('urut')->get();
        
        return view('pengaturan.peraturan.index', compact('peraturanUmum', 'peraturanRuangBaca'));
    }

    public function create()
    {
        return view('pengaturan.peraturan.create');
    }

    public function store(Request $request)
    {
       $validated = $request->validate([
            'urut' => 'required|integer|min:1|unique:peraturans,urut,NULL,id,tipe,'.$request->tipe,
            'text' => 'required|string|max:1000',
            'tipe' => 'required|in:1,2',
        ], [
            'urut.required' => 'Kolom urutan wajib diisi',
            'urut.integer' => 'Urutan harus berupa bilangan bulat',
            'urut.min' => 'Urutan minimal 1',
            'urut.unique' => 'Nomor urutan sudah digunakan untuk tipe peraturan ini',
            'text.required' => 'Kolom isi peraturan wajib diisi',
            'text.max' => 'Isi peraturan maksimal 1000 karakter',
            'tipe.required' => 'Tipe peraturan wajib dipilih',
            'tipe.in' => 'Tipe peraturan tidak valid',
        ]);

        Peraturan::create($validated);

        return redirect()->route('peraturan.index')
                         ->with('success', 'Peraturan berhasil ditambahkan');
    }

    public function edit(Peraturan $peraturan)
    {
        return view('pengaturan.peraturan.edit', compact('peraturan'));
    }

    public function update(Request $request, Peraturan $peraturan)
    {
       $validated = $request->validate([
            'urut' => 'required|integer|min:1|unique:peraturans,urut,'.$peraturan->id.',id,tipe,'.$request->tipe,
            'text' => 'required|string|max:1000',
            'tipe' => 'required|in:1,2',
        ], [
            'urut.required' => 'Kolom urutan wajib diisi',
            'urut.integer' => 'Urutan harus berupa bilangan bulat',
            'urut.min' => 'Urutan minimal 1',
            'urut.unique' => 'Nomor urutan sudah digunakan untuk tipe peraturan ini',
            'text.required' => 'Kolom isi peraturan wajib diisi',
            'text.max' => 'Isi peraturan maksimal 1000 karakter',
            'tipe.required' => 'Tipe peraturan wajib dipilih',
            'tipe.in' => 'Tipe peraturan tidak valid',
        ]);

        $peraturan->update($validated);

        return redirect()->route('peraturan.index')
                         ->with('success', 'Peraturan berhasil diupdate');
    }

    public function destroy(Peraturan $peraturan)
    {
        $peraturan->delete();

        return redirect()->route('peraturan.index')
                         ->with('success', 'Peraturan berhasil dihapus');
    }
}