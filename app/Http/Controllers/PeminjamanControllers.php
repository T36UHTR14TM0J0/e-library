<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanControllers extends Controller
{
    /**
     * Menampilkan daftar peminjaman
     */
    public function index()
    {
        $peminjamans = Peminjaman::with(['buku', 'user'])
            ->when(!auth()->user()->isAdmin(), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('peminjaman.index', compact('peminjamans'));
    }


    /**
     * Menyimpan data peminjaman baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after:tanggal_pinjam',
            'catatan' => 'nullable|string|max:255',
        ]);

        // Check book availability
        $buku = Buku::findOrFail($request->buku_id);
        
        if ($buku->tersedia() <= 0) {
            return redirect()->back()
                ->with('error', 'Buku tidak tersedia untuk dipinjam');
        }

        // Check if user already borrowed this specific book
        $existingSameBook = Peminjaman::where('user_id', Auth::id())
            ->where('buku_id', $request->buku_id)
            ->whereIn('status', ['menunggu', 'dipinjam', 'terlambat'])
            ->exists();

        if ($existingSameBook) {
            return redirect()->back()
                ->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya');
        }

        // Check maximum different books (max 2 different titles)
        $maxDifferentBooks = 2;
        $currentDifferentBooks = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['menunggu', 'dipinjam', 'terlambat'])
            ->distinct('buku_id')
            ->count('buku_id');

        if ($currentDifferentBooks >= $maxDifferentBooks) {
            return redirect()->back()
                ->with('error', 'Anda telah mencapai batas maksimal peminjaman 2 buku berbeda');
        }

        // Validate due date based on user role
        $isDosen = auth()->user()->isDosen(); // Assuming you have this field
        $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
        $tanggalJatuhTempo = Carbon::parse($request->tanggal_jatuh_tempo);
        
        if ($isDosen) {
            $maxDueDate = $tanggalPinjam->copy()->addMonths(6); // 1 semester for lecturers
            if ($tanggalJatuhTempo->gt($maxDueDate)) {
                return redirect()->back()
                    ->with('error', 'Maksimal jatuh tempo untuk dosen adalah 6 bulan (1 semester)');
            }
        } 
         $isMahasiswa = auth()->user()->isMahasiswa(); // Assuming you have this field
        if($isMahasiswa) {
            $maxDueDate = $tanggalPinjam->copy()->addWeek(); // 1 week for students
            if ($tanggalJatuhTempo->gt($maxDueDate)) {
                return redirect()->back()
                    ->with('error', 'Maksimal jatuh tempo untuk mahasiswa adalah 1 minggu');
            }
        }

        // Process the loan
        $peminjaman = new Peminjaman();
        $peminjaman->user_id = Auth::id();
        $peminjaman->buku_id = $request->buku_id;
        $peminjaman->status = 'menunggu';
        $peminjaman->tanggal_pinjam = $request->tanggal_pinjam;
        $peminjaman->tanggal_jatuh_tempo = $request->tanggal_jatuh_tempo;
        $peminjaman->catatan = $request->catatan;
        $peminjaman->save();

        return redirect()->route('KatalogBuku.index')
            ->with('success', 'Peminjaman berhasil diajukan');
    }

    /**
     * Menampilkan detail peminjaman
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['buku', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Membatalkan peminjaman
     */
    public function cancel($id)
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'menunggu')
            ->findOrFail($id);

        $peminjaman->status = 'dibatalkan';
        $peminjaman->save();

        return redirect()->back()
            ->with('success', 'Peminjaman berhasil dibatalkan');
    }
}