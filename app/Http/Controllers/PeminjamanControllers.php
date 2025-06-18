<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

class PeminjamanControllers extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['buku', 'user'])
            ->when(!auth()->user()->isAdmin(), fn($query) => $query->where('user_id', auth()->id()))
            ->when(request('status'), fn($query, $status) => $query->where('status', $status))
            ->when(!request('status'), fn($query) => $query->whereIn('status', ['menunggu', 'dipinjam']))
            ->when(request('search'), function($query, $search) {
                return $query->whereHas('buku', fn($q) => $q->where('judul', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%"));
            })
            ->when(request('date_from'), fn($query, $date) => $query->whereDate('tanggal_pinjam', '>=', $date))
            ->when(request('date_to'), fn($query, $date) => $query->whereDate('tanggal_pinjam', '<=', $date))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('peminjaman.index', compact('peminjamans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_id'               => 'required|exists:bukus,id',
            'tanggal_pinjam'        => 'required|date',
            'tanggal_jatuh_tempo'   => 'required|date|after:tanggal_pinjam',
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

        $isDosen            = auth()->user()->role === 'dosen';
        $isMahasiswa        = auth()->user()->role === 'mahasiswa';
        $tanggalPinjam      = Carbon::parse($request->tanggal_pinjam);
        $tanggalJatuhTempo  = Carbon::parse($request->tanggal_jatuh_tempo);
        
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
        
        // Validasi status
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Hanya bisa mengembalikan buku yang berstatus dipinjam');
        }

        // Hitung denda jika tanggal pengembalian melebihi jatuh tempo
        $denda = 0;
        $tanggalKembali = now();
        $jatuhTempo = $peminjaman->tanggal_jatuh_tempo;
        
        if ($tanggalKembali->gt($jatuhTempo)) {
            $hariTerlambat = $tanggalKembali->diffInDays($jatuhTempo);
            $denda = $hariTerlambat * 1000; // Rp 1000 per hari keterlambatan
        }

        // Update data peminjaman
        $updateData = [
            'status'          => 'dikembalikan',
            'tanggal_kembali' => $tanggalKembali,
            'catatan_kembali' => $request->return_notes,
            'denda'           => $denda,
        ];

        // Kembalikan stok buku
        $peminjaman->buku->increment('jumlah');

        // Simpan perubahan
        $peminjaman->update($updateData);

        return back()->with('success', 'Pengembalian buku berhasil dikonfirmasi. ' . 
            ($denda > 0 ? "Denda yang harus dibayar: Rp " . number_format($denda, 0, ',', '.') : ''));
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

    public function cetakPDF($id)
    {
        // Ambil data peminjaman
        $peminjaman = Peminjaman::with(['buku', 'user', 'disetujui'])->findOrFail($id);
        
        // Render view ke HTML
        $html = View::make('peminjaman.cetak', compact('peminjaman'))->render();
        
        // Buat instance mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 20,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        
        // Header dan Footer
        $mpdf->SetHTMLHeader('
        <div style="text-align: center; font-weight: bold;">
            PERPUSTAKAAN UNIVERSITAS - DETAIL PEMINJAMAN
        </div>');
        
        $mpdf->SetHTMLFooter('
        <table width="100%">
            <tr>
                <td width="33%">{DATE j-m-Y}</td>
                <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right;">Dicetak oleh: '.auth()->user()->nama_lengkap.'</td>
            </tr>
        </table>');
        
        // Tulis konten HTML
        $mpdf->WriteHTML($html);
        
        // Output PDF
        return response()->streamDownload(function() use ($mpdf) {
            $mpdf->Output();
        }, 'detail_peminjaman_'.$peminjaman->id.'.pdf');
    }
}