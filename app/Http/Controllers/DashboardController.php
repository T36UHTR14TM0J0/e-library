<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Ebook;
use App\Models\Peminjaman;
use App\Models\EbookReading;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung rata-rata peminjaman per hari dalam 30 hari terakhir
        $startDate = now()->subDays(30)->format('Y-m-d');
        $endDate = now()->format('Y-m-d');
        
        $dailyLoans = Peminjaman::selectRaw('DATE(tanggal_pinjam) as date, COUNT(*) as count')
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Siapkan data untuk chart
        $chartLabels = [];
        $chartData = [];
        
        $currentDate = Carbon::parse($startDate);
        while ($currentDate <= Carbon::parse($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');
            $chartLabels[] = $currentDate->format('d M');
            
            $loanData = $dailyLoans->firstWhere('date', $dateStr);
            $chartData[] = $loanData ? $loanData->count : 0;
            
            $currentDate->addDay();
        }

        // Top 10 pengguna sering meminjam
        $topPeminjam = Peminjaman::selectRaw('user_id, count(*) as total_peminjaman')
            ->with('user:id,nama_lengkap')
            ->where('status', '!=', 'dibatalkan')
            ->groupBy('user_id')
            ->orderByDesc('total_peminjaman')
            ->limit(10)
            ->get();

        // Top 10 pengguna sering membaca ebook
        $topPembaca = EbookReading::selectRaw('user_id, count(*) as total_bacaan')
            ->with('user:id,nama_lengkap')
            ->groupBy('user_id')
            ->orderByDesc('total_bacaan')
            ->limit(10)
            ->get();
        
        return view('dashboard', [
            'totalBooks'        => Buku::count(),
            'totalEbook'        => Ebook::count(),
            'totalUsers'        => User::count(),
            'activeUsers'       => User::where('status_aktif', '1')->count(),
            'inactiveUsers'     => User::where('status_aktif', '0')->count(),
            'peminjamanBuku'    => Peminjaman::where('status','dipinjam')->count(),
            'TenggatList'       => Peminjaman::with(['buku', 'user'])
                                    ->where('status', 'dipinjam')
                                    ->where('tanggal_jatuh_tempo', '<', now())
                                    ->orderBy('tanggal_jatuh_tempo', 'asc')
                                    ->get(),
            'TopBukuDipinjam'   => Peminjaman::selectRaw('buku_id, count(*) as total_peminjaman')
                                    ->with('buku')
                                    ->groupBy('buku_id')
                                    ->orderByDesc('total_peminjaman')
                                    ->limit(5)
                                    ->get(),
            'topPeminjam'       => $topPeminjam,
            'topPembaca'        => $topPembaca,
            'chartData'         => [
                'labels' => $chartLabels,
                'data' => $chartData,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }

    public function getLoanChartData(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);
        
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];
        
        $dailyLoans = Peminjaman::selectRaw('DATE(tanggal_pinjam) as date, COUNT(*) as count')
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Siapkan data untuk chart
        $chartLabels = [];
        $chartData = [];
        
        $currentDate = Carbon::parse($startDate);
        while ($currentDate <= Carbon::parse($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');
            $chartLabels[] = $currentDate->format('d M');
            
            $loanData = $dailyLoans->firstWhere('date', $dateStr);
            $chartData[] = $loanData ? $loanData->count : 0;
            
            $currentDate->addDay();
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $chartLabels,
                'data' => $chartData
            ]
        ]);
    }


   public function filterDataPeminjaman(Request $request)
    {
        try {
            // Validate request parameters
            $validated = $request->validate([
                'filter_type' => 'required|in:daily,weekly,monthly,yearly',
                'filter_value' => 'required',
                'page' => 'sometimes|integer|min:1'
            ]);

            $filterType     = $validated['filter_type'];
            $filterValue    = $validated['filter_value'];
            $query = Peminjaman::with(['user:id,nama_lengkap', 'buku:id,judul'])
                        ->select([
                            'id',
                            'user_id',
                            'buku_id',
                            'status',
                            'tanggal_pinjam',
                            'tanggal_kembali',
                            'tanggal_jatuh_tempo',
                            'denda'
                        ]);

            switch ($filterType) {
                case 'daily':
                    // Validate date format
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $filterValue)) {
                        throw ValidationException::withMessages([
                            'filter_value' => 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD'
                        ]);
                    }

                    $date = Carbon::parse($filterValue)->toDateString();
                    $query->whereDate('tanggal_pinjam', $date);
                break;
                    
                case 'weekly':
                    // Validate week format (YYYY-Www)
                    if (!preg_match('/^(\d{4})-W(\d{2})$/', $filterValue, $matches)) {
                        throw ValidationException::withMessages([
                            'filter_value' => 'Format minggu tidak valid. Gunakan format YYYY-Www (contoh: 2025-W26)'
                        ]);
                    }

                    $year       = (int)$matches[1];
                    $weekNum    = (int)$matches[2];
                    
                    // Validate week number range (1-53)
                    if ($weekNum < 1 || $weekNum > 53) {
                        throw ValidationException::withMessages([
                            'filter_value' => 'Nomor minggu harus antara 1 dan 53'
                        ]);
                    }

                    // Validate year range
                    $currentYear = (int)date('Y');
                    if ($year < 2000 || $year > $currentYear + 1) {
                        throw ValidationException::withMessages([
                            'filter_value' => "Tahun harus antara 2000 dan " . ($currentYear + 1)
                        ]);
                    }

                    $date           = Carbon::now()->setISODate($year, $weekNum);
                    $startOfWeek    = $date->startOfWeek()->format('Y-m-d');
                    $endOfWeek      = $date->endOfWeek()->format('Y-m-d');
                    
                    $query->whereBetween('tanggal_pinjam', [$startOfWeek, $endOfWeek]);
                break;

                $year = $matches[1];
                $weekNum = $matches[2];
                
                // Validate week number
                if ($weekNum < 1 || $weekNum > 53) {
                    throw ValidationException::withMessages([
                        'filter_value' => 'Nomor minggu harus antara 1 dan 53'
                    ]);
                }

                $date = Carbon::createFromFormat('Y-W', $filterValue);
                $startOfWeek = $date->startOfWeek()->format('Y-m-d');
                $endOfWeek = $date->endOfWeek()->format('Y-m-d');
                
                $query->whereBetween('tanggal_pinjam', [$startOfWeek, $endOfWeek]);
            break;

                    
                case 'monthly':
                    // Validate month format (YYYY-MM)
                    if (!preg_match('/^(\d{4})-(\d{2})$/', $filterValue, $matches)) {
                        throw ValidationException::withMessages([
                            'filter_value' => 'Format bulan tidak valid. Gunakan format YYYY-MM'
                        ]);
                    }

                    $year       = $matches[1];
                    $month      = $matches[2];
                    $date       = Carbon::createFromFormat('Y-m', $filterValue);
                    $monthName  = $date->locale('id')->monthName;
                    
                    $query->whereYear('tanggal_pinjam', $year)
                        ->whereMonth('tanggal_pinjam', $month);
                break;
                    
                case 'yearly':
                    // Validate year format (YYYY)
                    if (!preg_match('/^\d{4}$/', $filterValue)) {
                        throw ValidationException::withMessages([
                            'filter_value' => 'Format tahun tidak valid. Gunakan format YYYY'
                        ]);
                    }

                    $year = (int)$filterValue;
                    $currentYear = (int)date('Y');

                    if ($year < 2000 || $year > $currentYear + 1) {
                        throw ValidationException::withMessages([
                            'filter_value' => "Tahun harus antara 2000 dan " . ($currentYear + 1)
                        ]);
                    }

                    $query->whereYear('tanggal_pinjam', $year);
                break;
            }

            $peminjamans = $query->orderBy('tanggal_pinjam', 'desc')
                            ->paginate(10) // Fixed 10 items per page
                            ->through(function ($item) {
                                $isOverdue = now()->greaterThan($item->tanggal_jatuh_tempo);
                                
                                return [
                                    'id' => $item->id,
                                    'user' => $item->user ? $item->user->only(['id', 'nama_lengkap']) : null,
                                    'buku' => $item->buku ? $item->buku->only(['id', 'judul']) : null,
                                    'tanggal_pinjam' => $item->tanggal_pinjam->format('Y-m-d'),
                                    'tanggal_kembali' => $item->tanggal_kembali ? $item->tanggal_kembali->format('Y-m-d') : null,
                                    'tanggal_jatuh_tempo' => $item->tanggal_jatuh_tempo->format('Y-m-d'),
                                    'status' => $item->status,
                                    'denda' => (float) $item->denda,
                                    'is_overdue' => $isOverdue,
                                    'days_overdue' => $isOverdue ? now()->diffInDays($item->tanggal_jatuh_tempo) : 0,
                                    'denda_formatted' => 'Rp ' . number_format($item->denda, 0, ',', '.')
                                ];
                            });

            return response()->json([
                'success' => true,
                'data' => $peminjamans->items(),
                'meta' => [
                    'total' => $peminjamans->total(),
                    'current_page' => $peminjamans->currentPage(),
                    'last_page' => $peminjamans->lastPage(),
                ],
                'links' => [
                    'prev_page_url' => $peminjamans->previousPageUrl(),
                    'next_page_url' => $peminjamans->nextPageUrl()
                ],
                'message' => 'Data peminjaman berhasil difilter'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
