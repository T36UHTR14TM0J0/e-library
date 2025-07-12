<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function index()
    {
        $informasis = Informasi::query()
            ->when(request('judul'), function($query) {
                $query->where('judul', 'like', '%'.request('judul').'%');
            })
            ->when(request('min_kapasitas'), function($query) {
                $query->where('maks_kapasitas', '>=', request('min_kapasitas'));
            })
            ->when(request('maks_kapasitas'), function($query) {
                $query->where('min_kapasitas', '<=', request('maks_kapasitas'));
            })
            ->orderBy('judul', 'asc')
            ->paginate(10);
        
        return view('pengaturan.informasi.index', compact('informasis'));
    }

    public function create()
    {
        return view('pengaturan.informasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|max:255',
            'icon' => 'required|max:50',
            'warna' => 'required|in:secondary,info,success,warning,danger',
            'min_kapasitas' => 'required|numeric|min:0',
            'maks_kapasitas' => 'required|numeric|min:0|gte:min_kapasitas',
            'waktu_buka' => 'required|date_format:H:i',
            'waktu_tutup' => 'required|date_format:H:i|after:waktu_buka',
            'info' => 'nullable|max:255',
            'fasilitas' => 'required|array|min:1',
            'fasilitas.*' => 'required|string|max:100'
        ], [
            'judul.required' => 'Judul fasilitas wajib diisi',
            'judul.max' => 'Judul maksimal 255 karakter',
            'icon.required' => 'Icon wajib diisi',
            'icon.max' => 'Icon maksimal 50 karakter',
            'warna.required' => 'Warna wajib dipilih',
            'warna.in' => 'Warna yang dipilih tidak valid',
            'min_kapasitas.required' => 'Kapasitas minimum wajib diisi',
            'min_kapasitas.numeric' => 'Kapasitas harus berupa angka',
            'min_kapasitas.min' => 'Kapasitas minimum tidak boleh negatif',
            'maks_kapasitas.required' => 'Kapasitas maksimum wajib diisi',
            'maks_kapasitas.numeric' => 'Kapasitas harus berupa angka',
            'maks_kapasitas.min' => 'Kapasitas maksimum tidak boleh negatif',
            'maks_kapasitas.gte' => 'Kapasitas maksimum harus lebih besar atau sama dengan minimum',
            'waktu_buka.required' => 'Waktu buka wajib diisi',
            'waktu_buka.date_format' => 'Format waktu buka tidak valid',
            'waktu_tutup.required' => 'Waktu tutup wajib diisi',
            'waktu_tutup.date_format' => 'Format waktu tutup tidak valid',
            'waktu_tutup.after' => 'Waktu tutup harus setelah waktu buka',
            'info.max' => 'Info tambahan maksimal 255 karakter',
            'fasilitas.required' => 'Minimal satu fasilitas harus diisi',
            'fasilitas.array' => 'Format fasilitas tidak valid',
            'fasilitas.min' => 'Minimal satu fasilitas harus diisi',
            'fasilitas.*.required' => 'Fasilitas tidak boleh kosong',
            'fasilitas.*.max' => 'Fasilitas maksimal 100 karakter'
        ]);
        $validated['fasilitas'] = implode(', ', $validated['fasilitas']);
        Informasi::create($validated);

        return redirect()->route('informasi.index')
                        ->with('success', 'Informasi berhasil ditambahkan');
    }

    public function edit(Informasi $informasi)
    {
        return view('pengaturan.informasi.edit', compact('informasi'));
    }

    public function update(Request $request, Informasi $informasi)
    {
        $validated = $request->validate([
            'judul' => 'required|max:255',
            'icon' => 'required|max:50',
            'warna' => 'required|in:secondary,info,success,warning,danger',
            'min_kapasitas' => 'required|numeric|min:0',
            'maks_kapasitas' => 'required|numeric|min:0|gte:min_kapasitas',
            'waktu_buka' => 'required|date_format:H:i',
            'waktu_tutup' => 'required|date_format:H:i|after:waktu_buka',
            'info' => 'nullable|max:255',
            'fasilitas' => 'required|array|min:1',
            'fasilitas.*' => 'required|string|max:100'
        ], [
            'judul.required' => 'Judul fasilitas wajib diisi',
            'judul.max' => 'Judul maksimal 255 karakter',
            'icon.required' => 'Icon wajib diisi',
            'icon.max' => 'Icon maksimal 50 karakter',
            'warna.required' => 'Warna wajib dipilih',
            'warna.in' => 'Warna yang dipilih tidak valid',
            'min_kapasitas.required' => 'Kapasitas minimum wajib diisi',
            'min_kapasitas.numeric' => 'Kapasitas harus berupa angka',
            'min_kapasitas.min' => 'Kapasitas minimum tidak boleh negatif',
            'maks_kapasitas.required' => 'Kapasitas maksimum wajib diisi',
            'maks_kapasitas.numeric' => 'Kapasitas harus berupa angka',
            'maks_kapasitas.min' => 'Kapasitas maksimum tidak boleh negatif',
            'maks_kapasitas.gte' => 'Kapasitas maksimum harus lebih besar atau sama dengan minimum',
            'waktu_buka.required' => 'Waktu buka wajib diisi',
            'waktu_buka.date_format' => 'Format waktu buka tidak valid',
            'waktu_tutup.required' => 'Waktu tutup wajib diisi',
            'waktu_tutup.date_format' => 'Format waktu tutup tidak valid',
            'waktu_tutup.after' => 'Waktu tutup harus setelah waktu buka',
            'info.max' => 'Info tambahan maksimal 255 karakter',
            'fasilitas.required' => 'Minimal satu fasilitas harus diisi',
            'fasilitas.array' => 'Format fasilitas tidak valid',
            'fasilitas.min' => 'Minimal satu fasilitas harus diisi',
            'fasilitas.*.required' => 'Fasilitas tidak boleh kosong',
            'fasilitas.*.max' => 'Fasilitas maksimal 100 karakter'
        ]);

        // Convert fasilitas array to comma-separated string
        $validated['fasilitas'] = implode(', ', $validated['fasilitas']);

        // Update facility record
        $informasi->update($validated);

        return redirect()->route('informasi.index')
                        ->with('success', 'Informasi berhasil diperbarui');
    }

    public function show(Informasi $informasi)
    {
        // Format waktu untuk tampilan
        $waktu_buka = \Carbon\Carbon::parse($informasi->waktu_buka)->format('H:i');
        $waktu_tutup = \Carbon\Carbon::parse($informasi->waktu_tutup)->format('H:i');
        
        // Format fasilitas sebagai array
        $fasilitas = $informasi->fasilitas ? explode(', ', $informasi->fasilitas) : [];
        
        return view('pengaturan.informasi.show', compact('informasi', 'waktu_buka', 'waktu_tutup', 'fasilitas'));
    }

    public function destroy(Informasi $informasi)
    {
        $informasi->delete();

        return redirect()->route('informasi.index')
                        ->with('success', 'Informasi berhasil dihapus');
    }
}