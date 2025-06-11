<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdiRequest;
use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodi = Prodi::query()
    ->when(request('nama'), function($query) {
        return $query->where('nama', 'like', '%' . request('nama') . '%');
    })
    ->when(request('kode'), function($query) {
        return $query->where('kode', 'like', '%' . request('kode') . '%');
    })
    ->latest()
    ->paginate(10);

        return view('pengaturan.prodi.index',compact('prodi'));
    }

    public function create()
    {
        return view('pengaturan.prodi.create');
    }

    public function store(ProdiRequest $request)
    {
        try {
            Prodi::create($request->validated());

            return redirect()->route('prodi.index')
                   ->with('success', 'Prodi berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function edit($id)
    {
        $prodi = Prodi::findOrFail($id);

        return view('pengaturan.prodi.edit',compact('prodi'));
    }


    public function update(ProdiRequest $request, Prodi $prodi)
    {
        try{
            $prodi->update($request->validated());

            return redirect()->route('prodi.index')->with('success', 'prodi berhasil diubah');
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('prodi.index')
                   ->with('error', 'prodi tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('prodi.index')
                   ->with('error', 'Gagal mengubah prodi: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $prodi = Prodi::findOrFail($id);
            $prodi->delete();

            return redirect()->route('prodi.index')
                   ->with('success', 'prodi berhasil dihapus');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('prodi.index')
                   ->with('error', 'prodi tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('prodi.index')
                   ->with('error', 'Gagal menghapus prodi: ' . $e->getMessage());
        }
    }
}
