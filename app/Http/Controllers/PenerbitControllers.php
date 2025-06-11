<?php

namespace App\Http\Controllers;

use App\Models\Penerbit;
use App\Http\Requests\PenerbitRequest;
use Illuminate\Http\Request;

class PenerbitControllers extends Controller
{
    public function index()
    {
        $penerbit = Penerbit::query()
                    ->when(request('kode'), function($query) {
                        return $query->where('kode', 'like', '%' . request('kode') . '%');
                    })
                    ->when(request('nama'), function($query) {
                        return $query->where('nama', 'like', '%' . request('nama') . '%');
                    })
                    ->latest()
                    ->orderBy('created_at','desc')
                    ->paginate(10);

        return view('pengaturan.penerbit.index', compact('penerbit'));
    }

    public function create()
    {
        // Ambil kode terakhir dari database
        $lastPenerbit = Penerbit::orderBy('kode_penerbit', 'desc')->first();
        
        // Jika tidak ada data, mulai dari PUB001
        if (!$lastPenerbit) {
            $kode = 'PUB001';
        } else {
            // Ekstrak angka dari kode terakhir
            $lastCode = $lastPenerbit->kode_penerbit;
            $number = (int) substr($lastCode, 3); // Hilangkan 'PUB' dan ambil angkanya
            $nextNumber = $number + 1;
            
            // Format ulang dengan leading zeros
            $kode = 'PUB' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return view('pengaturan.penerbit.create', compact('kode'));
    }

    public function store(PenerbitRequest $request)
    {
        try {
            // Ambil kode terakhir
            $lastPenerbit = Penerbit::orderBy('kode_penerbit', 'desc')->first();
            
            if (!$lastPenerbit) {
                $kode = 'PUB001';
            } else {
                $lastCode = $lastPenerbit->kode_penerbit;
                $number = (int) substr($lastCode, 3);
                $nextNumber = $number + 1;
                $kode = 'PUB' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }

            $data = $request->validated();
            $data['kode'] = $kode;

            Penerbit::create($data);

            return redirect()->route('penerbit.index')
                ->with('success', 'Penerbit berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $penerbit = Penerbit::findOrFail($id);

        return view('pengaturan.penerbit.edit', compact('penerbit'));
    }

    public function update(PenerbitRequest $request, Penerbit $penerbit)
    {
        try {
            $penerbit->update($request->validated());

            return redirect()->route('penerbit.index')
                   ->with('success', 'Penerbit berhasil diubah');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('penerbit.index')
                   ->with('error', 'Penerbit tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('penerbit.index')
                   ->with('error', 'Gagal mengubah penerbit: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $penerbit = Penerbit::findOrFail($id);
            $penerbit->delete();

            return redirect()->route('penerbit.index')
                   ->with('success', 'Penerbit berhasil dihapus');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('penerbit.index')
                   ->with('error', 'Penerbit tidak ditemukan');

        } catch (\Exception $e) {
            return redirect()->route('penerbit.index')
                   ->with('error', 'Gagal menghapus penerbit: ' . $e->getMessage());
        }
    }
}