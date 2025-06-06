<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriRequest;
use App\Models\cr;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::query()
        ->when(request('nama'), function($query) {
            return $query->where('nama', 'like', '%' . request('nama') . '%');
        })
        ->latest()
        ->paginate(10);

        return view('kategori.index',compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KategoriRequest $request)
    {
        try {
            Kategori::create($request->validated());

            return redirect()->route('kategori.index')
                   ->with('success', 'Data kategori berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);

        return view('kategori.edit',compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KategoriRequest $request, Kategori $kategori)
    {
         try{
            $kategori->update($request->validated());

            return redirect()->route('kategori.index')->with('success', 'Data kategori berhasil diubah');
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('kategori.index')
                   ->with('error', 'kategori tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('kategori.index')
                   ->with('error', 'Gagal mengubah kategori: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();

            return redirect()->route('kategori.index')
                   ->with('success', 'Data kategori berhasil dihapus');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('kategori.index')
                   ->with('error', 'kategori tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('kategori.index')
                   ->with('error', 'Gagal menghapus prodi: ' . $e->getMessage());
        }
    }
}
