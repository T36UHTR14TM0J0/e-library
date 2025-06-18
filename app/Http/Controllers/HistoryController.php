<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\EbookReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class HistoryController extends Controller
{
    public function index()
    {
        return $this->filter(new Request());
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $type = $request->input('type', 'all');
        $status = $request->input('status', 'all');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Inisialisasi variabel paginator
        $peminjamans = null;
        $ebookReadings = null;

        if ($type === 'all' || $type === 'peminjaman') {
            $peminjamanQuery = Peminjaman::with(['buku', 'disetujui', 'dibatalkan_oleh'])
                ->where('user_id', $user->id);

            if ($status !== 'all') {
                $peminjamanQuery->where('status', $status);
            }

            if ($dateFrom) {
                $peminjamanQuery->whereDate('created_at', '>=', $dateFrom);
            }

            if ($dateTo) {
                $peminjamanQuery->whereDate('created_at', '<=', $dateTo);
            }

            $peminjamans = $peminjamanQuery->orderBy('created_at', 'desc')->paginate(10);
        }

        if ($type === 'all' || $type === 'ebook') {
            $ebookQuery = EbookReading::with('ebook')
                ->where('user_id', $user->id);

            if ($dateFrom) {
                $ebookQuery->whereDate('created_at', '>=', $dateFrom);
            }

            if ($dateTo) {
                $ebookQuery->whereDate('created_at', '<=', $dateTo);
            }

            $ebookReadings = $ebookQuery->orderBy('created_at', 'desc')->paginate(10);
        }

        // Jika tidak ada data, buat paginator kosong untuk menghindari error
        if (!$peminjamans) {
            $peminjamans = new LengthAwarePaginator([], 0, 10);
        }

        if (!$ebookReadings) {
            $ebookReadings = new LengthAwarePaginator([], 0, 10);
        }

        return view('histori.index', [
            'peminjamans' => $peminjamans,
            'ebookReadings' => $ebookReadings,
            'type' => $type,
            'status' => $status,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ]);
    }
}