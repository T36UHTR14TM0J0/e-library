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
        
        if ($buku->jumlah <= 0) {
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
        $isDosen = auth()->user()->role === 'dosen';
        $isMahasiswa = auth()->user()->role === 'mahasiswa';
        
        $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
        $tanggalJatuhTempo = Carbon::parse($request->tanggal_jatuh_tempo);
        
        if ($isDosen) {
            $maxDueDate = $tanggalPinjam->copy()->addMonths(6);
            if ($tanggalJatuhTempo->gt($maxDueDate)) {
                return redirect()->back()
                    ->with('error', 'Maksimal jatuh tempo untuk dosen adalah 6 bulan (1 semester)');
            }
        } elseif ($isMahasiswa) {
            $maxDueDate = $tanggalPinjam->copy()->addWeek();
            if ($tanggalJatuhTempo->gt($maxDueDate)) {
                return redirect()->back()
                    ->with('error', 'Maksimal jatuh tempo untuk mahasiswa adalah 1 minggu');
            }
        }

        // Process the loan
        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $request->buku_id,
            'status' => 'menunggu',
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'catatan' => $request->catatan,
        ]);

         $peminjaman->buku->decrement('jumlah');

        return redirect()->route('KatalogBuku.index')
            ->with('success', 'Peminjaman berhasil diajukan');
    }

    /**
     * Menyetujui peminjaman (admin only)
     */
    public function approve($id)
    {
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);
        
        // Validasi status
        if ($peminjaman->status !== 'menunggu') {
            return redirect()->back()
                ->with('error', 'Hanya bisa menyetujui peminjaman yang berstatus menunggu');
        }

        // Validasi stok buku
        if ($peminjaman->buku->jumlah <= 0) {
            return redirect()->back()
                ->with('error', 'Stok buku tidak mencukupi untuk disetujui');
        }

        // Update status dan tanggal
        $peminjaman->update([
            'status' => 'dipinjam',
            // 'tanggal_pinjam' => now(),
            // 'tanggal_jatuh_tempo' => now()->addWeek(), // 1 minggu untuk semua
            // 'disetujui_oleh' => Auth::id(),
            // 'disetujui_pada' => now()
        ]);
        
        // Kurangi stok buku
        $peminjaman->buku->decrement('jumlah');
        
        return redirect()->back()
            ->with('success', 'Peminjaman telah disetujui');
    }

    /**
     * Menolak peminjaman (admin only)
     */
    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Validasi status
        if ($peminjaman->status !== 'menunggu') {
            return redirect()->back()
                ->with('error', 'Hanya bisa menolak peminjaman yang berstatus menunggu');
        }

        $peminjaman->update([
            'status'    => 'dibatalkan',
            'catatan'   => 'Ditolak oleh admin',
            // 'ditolak_oleh' => Auth::id(),
            // 'ditolak_pada' => now()
        ]);
        
        return redirect()->back()
            ->with('success', 'Peminjaman telah ditolak');
    }

    /**
     * Konfirmasi pengembalian buku (admin only)
     */
    public function returnBook(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);
        
        // Validasi status
        if ($peminjaman->status !== 'dipinjam' && $peminjaman->status !== 'terlambat') {
            return redirect()->back()
                ->with('error', 'Hanya bisa mengembalikan buku yang berstatus dipinjam atau terlambat');
        }

        $updateData = [
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
            // 'kondisi_buku' => $request->condition,
            // 'catatan_pengembalian' => $request->return_notes,
            // 'dikembalikan_oleh' => Auth::id()
        ];

        // Jika terlambat, hitung denda
        if ($peminjaman->isLate()) {
            $daysLate = now()->diffInDays($peminjaman->tanggal_jatuh_tempo);
            $denda = $daysLate * 1000; // Rp 5000 per hari
            $updateData['denda'] = $denda;
        }

        $peminjaman->update($updateData);
    
        
        return redirect()->back()
            ->with('success', 'Pengembalian buku telah dikonfirmasi');
    }

    /**
     * Membatalkan peminjaman (user only)
     */
    public function cancel($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Validasi kepemilikan
        if ($peminjaman->user_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk membatalkan peminjaman ini');
        }

        // Validasi status
        if ($peminjaman->status !== 'menunggu') {
            return redirect()->back()
                ->with('error', 'Hanya bisa membatalkan peminjaman yang berstatus menunggu');
        }
        
        $peminjaman->update([
            'status' => 'dibatalkan',
            'catatan' => 'Dibatalkan oleh peminjam',
        ]);
        
        return redirect()->back()
            ->with('success', 'Peminjaman telah dibatalkan');
    }
}