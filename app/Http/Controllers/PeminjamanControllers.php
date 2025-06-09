<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanControllers extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['buku', 'user'])
            ->when(!auth()->user()->isAdmin(), fn($query) => $query->where('user_id', auth()->id()))
            ->whereNotIn('status', ['dibatalkan', 'dikembalikan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('peminjaman.index', compact('peminjamans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_id'               => 'required|exists:bukus,id',
            'tanggal_pinjam'        => 'required|date',
            'tanggal_jatuh_tempo'  => 'required|date|after:tanggal_pinjam',
            'catatan_pinjam'        => 'nullable|string|max:255',
        ]);

        $buku = Buku::findOrFail($request->buku_id);
        
        if ($buku->jumlah <= 0) {
            return back()->with('error', 'Buku tidak tersedia untuk dipinjam');
        }

        if (Peminjaman::where('user_id', Auth::id())
            ->where('buku_id', $request->buku_id)
            ->whereIn('status', ['menunggu', 'dipinjam', 'terlambat'])
            ->exists()) {
            return back()->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya');
        }

        $maxDifferentBooks = 2;
        $currentDifferentBooks = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['menunggu', 'dipinjam', 'terlambat'])
            ->distinct('buku_id')
            ->count('buku_id');

        if ($currentDifferentBooks >= $maxDifferentBooks) {
            return back()->with('error', 'Anda telah mencapai batas maksimal peminjaman 2 buku berbeda');
        }

        $isDosen = auth()->user()->role === 'dosen';
        $isMahasiswa = auth()->user()->role === 'mahasiswa';
        $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
        $tanggalJatuhTempo = Carbon::parse($request->tanggal_jatuh_tempo);
        
        if ($isDosen && $tanggalJatuhTempo->gt($tanggalPinjam->copy()->addMonths(6))) {
            return back()->with('error', 'Maksimal jatuh tempo untuk dosen adalah 6 bulan');
        } elseif ($isMahasiswa && $tanggalJatuhTempo->gt($tanggalPinjam->copy()->addWeek())) {
            return back()->with('error', 'Maksimal jatuh tempo untuk mahasiswa adalah 1 minggu');
        }

        Peminjaman::create([
            'user_id'               => Auth::id(),
            'buku_id'               => $request->buku_id,
            'status'                => 'menunggu',
            'tanggal_pinjam'        => $request->tanggal_pinjam,
            'tanggal_jatuh_tempo'   => $request->tanggal_jatuh_tempo,
            'catatan_pinjam'        => $request->catatan,
        ])->buku->decrement('jumlah');

        return redirect()->route('KatalogBuku.index')->with('success', 'Peminjaman berhasil diajukan');
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);
        
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Hanya bisa menyetujui peminjaman yang berstatus menunggu');
        }

        if ($peminjaman->buku->jumlah <= 0) {
            return back()->with('error', 'Stok buku tidak mencukupi untuk disetujui');
        }

        $peminjaman->update([
            'status'            => 'dipinjam',
            'tanggal_pinjam'    => now(),
            'disetujui_oleh'    => Auth::id(),
            'tanggal_setujui'   => now()
        ]);
        
        return back()->with('success', 'Peminjaman telah disetujui');
    }

    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Hanya bisa menolak peminjaman yang berstatus menunggu');
        }

        $peminjaman->update([
            'status'            => 'dibatalkan',
            'catatan_batal'     => 'Ditolak oleh admin',
            'dibatalkan_oleh'   => Auth::id(),
            'tanggal_batal'     => now()
        ]);

        $peminjaman->buku->increment('jumlah');
        
        return back()->with('success', 'Peminjaman telah ditolak');
    }

    public function returnBook(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);
        
        if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Hanya bisa mengembalikan buku yang berstatus dipinjam atau terlambat');
        }

        $updateData = [
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
            'catatan_kembali' => $request->return_notes,
        ];

        $updateData['denda'] = now()->isPast($peminjaman->tanggal_jatuh_tempo) 
        ? now()->diffInDays($peminjaman->tanggal_jatuh_tempo) * 1000 
        : 0;


        $peminjaman->buku->increment('jumlah');
        $peminjaman->update($updateData);
    
        return back()->with('success', 'Pengembalian buku telah dikonfirmasi');
    }

    public function cancel($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk membatalkan peminjaman ini');
        }

        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Hanya bisa membatalkan peminjaman yang berstatus menunggu');
        }
        
        $peminjaman->update([
            'status' => 'dibatalkan',
            'catatan' => 'Dibatalkan oleh peminjam',
        ]);

        $peminjaman->buku->increment('jumlah');
        
        return back()->with('success', 'Peminjaman telah dibatalkan');
    }


    public function show($id){
        $peminjaman = Peminjaman::with(['buku', 'user','disetujui','dibatalkan_oleh'])
            ->findOrFail($id);
            // dd($peminjaman)
        return view('peminjaman.show',compact('peminjaman'));
    }
}