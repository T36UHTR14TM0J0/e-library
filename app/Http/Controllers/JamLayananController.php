<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JamLayanan;

class JamLayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = JamLayanan::query();
    
        if(request()->has('hari')) {
            $query->where('hari', request('hari'));
        }
    
        $jamLayanans = $query->paginate(10);
        return view('pengaturan.jam_layanan.index', compact('jamLayanans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        return view('pengaturan.jam_layanan.create', compact('hariList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validasi = $request->validate([
            'hari' => 'required|string|max:255',
            'waktu_buka' => 'required|date_format:H:i',
            'waktu_tutup' => 'required|date_format:H:i|after:waktu_buka',
            'catatan' => 'nullable|string',
        ], [
            'hari.required' => 'Kolom hari wajib diisi',
            'hari.string' => 'Hari harus berupa teks',
            'hari.max' => 'Hari maksimal 255 karakter',
            'waktu_buka.required' => 'Waktu buka wajib diisi',
            'waktu_buka.date_format' => 'Format waktu buka tidak valid (HH:MM)',
            'waktu_tutup.required' => 'Waktu tutup wajib diisi',
            'waktu_tutup.date_format' => 'Format waktu tutup tidak valid (HH:MM)',
            'waktu_tutup.after' => 'Waktu tutup harus setelah waktu buka',
        ]);


        JamLayanan::create($validasi);

        return redirect()->route('jam_layanan.index')
                         ->with('success', 'Jam layanan berhasil ditambahkan');
    }

  

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JamLayanan $jamLayanan)
    {
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        return view('pengaturan.jam_layanan.edit', compact('jamLayanan', 'hariList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JamLayanan $jamLayanan)
    {
        $validasi = $request->validate([
            'hari' => 'required|string|max:255',
            'waktu_buka' => 'required|date_format:H:i',
            'waktu_tutup' => 'required|date_format:H:i|after:waktu_buka',
            'catatan' => 'nullable|string',
        ], [
            'hari.required' => 'Kolom hari wajib diisi',
            'hari.string' => 'Hari harus berupa teks',
            'hari.max' => 'Hari maksimal 255 karakter',
            'waktu_buka.required' => 'Waktu buka wajib diisi',
            'waktu_buka.date_format' => 'Format waktu buka tidak valid (HH:MM)',
            'waktu_tutup.required' => 'Waktu tutup wajib diisi',
            'waktu_tutup.date_format' => 'Format waktu tutup tidak valid (HH:MM)',
            'waktu_tutup.after' => 'Waktu tutup harus setelah waktu buka',
        ]);

        $jamLayanan->update($validasi);

        return redirect()->route('jam_layanan.index')
                         ->with('success', 'Jam layanan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JamLayanan $jamLayanan)
    {
        $jamLayanan->delete();

        return redirect()->route('jam_layanan.index')
                         ->with('success', 'Jam layanan berhasil dihapus');
    }
}