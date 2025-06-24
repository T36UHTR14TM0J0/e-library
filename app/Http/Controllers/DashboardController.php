<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Ebook;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard', [
            'totalBooks' => Buku::count(),
            'totalEbook' => Ebook::count(),
            'totalUsers' => User::count(),
            'activeUsers' => User::where('status_aktif', '1')->count(),
            'inactiveUsers' => User::where('status_aktif', '0')->count(),
            'peminjamanBuku' => Peminjaman::where('status','dipinjam')->count(),
            'overdueBooks' => Peminjaman::with(['buku', 'user'])
                ->where('status', 'dipinjam')
                ->where('tanggal_jatuh_tempo', '<', now())
                ->orderBy('tanggal_jatuh_tempo', 'asc')
                ->get(),
            'mostBorrowedBooks' => Peminjaman::selectRaw('buku_id, count(*) as total_peminjaman')
                ->with('buku')
                ->groupBy('buku_id')
                ->orderByDesc('total_peminjaman')
                ->limit(5) // Ambil 5 buku teratas
                ->get()
        ]);
    }


   public function filterDataPeminjaman(Request $request)
{
    try {
        // Validate request parameters
        $validated = $request->validate([
            'filter_type' => 'required|in:daily,weekly,monthly,yearly',
            'filter_value' => 'required'
        ]);

        $filterType = $validated['filter_type'];
        $filterValue = $validated['filter_value'];
        $additionalInfo = [];
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
                
                $additionalInfo = [
                    'date' => $date,
                    'day_name' => Carbon::parse($date)->locale('id')->dayName
                ];
                break;
                
            case 'weekly':
                // Validate week format (YYYY-Www)
                if (!preg_match('/^(\d{4})-W(\d{2})$/', $filterValue, $matches)) {
                    throw ValidationException::withMessages([
                        'filter_value' => 'Format minggu tidak valid. Gunakan format YYYY-Www (contoh: 2025-W27)'
                    ]);
                }

                $year = $matches[1];
                $weekNum = $matches[2];
                $maxWeeks = Carbon::create($year, 12, 28)->isoWeeksInYear();

                if ($weekNum < 1 || $weekNum > $maxWeeks) {
                    throw ValidationException::withMessages([
                        'filter_value' => "Nomor minggu harus antara 1 dan $maxWeeks untuk tahun $year"
                    ]);
                }

                $date = Carbon::createFromFormat('o-\WW', $filterValue);
                $startOfWeek = $date->startOfWeek()->format('Y-m-d');
                $endOfWeek = $date->endOfWeek()->format('Y-m-d');
                
                $query->whereBetween('tanggal_pinjam', [$startOfWeek, $endOfWeek]);
                
                $additionalInfo = [
                    'week_start' => $startOfWeek,
                    'week_end' => $endOfWeek,
                    'week_number' => (int)$weekNum,
                    'year' => $year,
                    'month' => $date->month
                ];
                break;
                
            case 'monthly':
                // Validate month format (YYYY-MM)
                if (!preg_match('/^(\d{4})-(\d{2})$/', $filterValue, $matches)) {
                    throw ValidationException::withMessages([
                        'filter_value' => 'Format bulan tidak valid. Gunakan format YYYY-MM'
                    ]);
                }

                $year = $matches[1];
                $month = $matches[2];

                if ($month < 1 || $month > 12) {
                    throw ValidationException::withMessages([
                        'filter_value' => 'Nomor bulan harus antara 1 dan 12'
                    ]);
                }

                $date = Carbon::createFromFormat('Y-m', $filterValue);
                $monthName = $date->locale('id')->monthName;
                
                $query->whereYear('tanggal_pinjam', $year)
                      ->whereMonth('tanggal_pinjam', $month);
                
                $additionalInfo = [
                    'year' => $year,
                    'month' => (int)$month,
                    'month_name' => $monthName,
                    'days_in_month' => $date->daysInMonth
                ];
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
                
                $additionalInfo = [
                    'year' => $year,
                    'is_current_year' => ($year == $currentYear),
                    'is_leap_year' => Carbon::create($year, 1, 1)->isLeapYear()
                ];
                break;
        }

        $peminjamans = $query->orderBy('tanggal_pinjam', 'desc')
                            ->get()
                            ->map(function ($item) {
                                $isOverdue = now()->greaterThan($item->tanggal_jatuh_tempo);
                                
                                return [
                                    'id' => $item->id,
                                    'user' => $item->user ? $item->user->only(['id', 'nama_lengkap']) : null,
                                    'buku' => $item->buku ? $item->buku->only(['id', 'judul']) : null,
                                    'tanggal_pinjam' => $item->tanggal_pinjam->format('Y-m-d'),
                                    'tanggal_kembali' => $item->tanggal_kembali ? $item->tanggal_kembali->format('Y-m-d') : null,
                                    'tanggal_jatuh_tempo' => $item->tanggal_jatuh_tempo->format('Y-m-d'),
                                    'status' => $item->status,
                                    'status_label' => $this->getStatusLabel($item->status),
                                    'denda' => (float) $item->denda,
                                    'is_overdue' => $isOverdue,
                                    'days_overdue' => $isOverdue ? now()->diffInDays($item->tanggal_jatuh_tempo) : 0,
                                    'denda_formatted' => 'Rp ' . number_format($item->denda, 0, ',', '.')
                                ];
                            });

        return response()->json([
            'success' => true,
            'data' => $peminjamans,
            'meta' => [
                'count' => $peminjamans->count(),
                'filter' => [
                    'type' => $filterType,
                    'value' => $filterValue,
                    'label' => $this->getFilterLabel($filterType, $filterValue, $additionalInfo)
                ],
                'additional_info' => $additionalInfo
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

// Helper method to get status label
private function getStatusLabel($status)
{
    $labels = [
        'menunggu' => 'Menunggu Persetujuan',
        'dipinjam' => 'Sedang Dipinjam',
        'dikembalikan' => 'Telah Dikembalikan',
        'dibatalkan' => 'Dibatalkan'
    ];
    
    return $labels[$status] ?? $status;
}

    // Helper method to generate filter label
    private function getFilterLabel($type, $value, $additionalInfo)
    {
        switch ($type) {
            case 'daily':
                return Carbon::parse($value)->locale('id')->translatedFormat('l, j F Y');
            case 'weekly':
                return "Minggu ke-{$additionalInfo['week_number']} ({$additionalInfo['week_start']} sampai {$additionalInfo['week_end']})";
            case 'monthly':
                return Carbon::createFromDate($additionalInfo['year'], $additionalInfo['month'], 1)
                    ->locale('id')->translatedFormat('F Y');
            case 'yearly':
                return "Tahun {$value}";
            default:
                return $value;
        }
    }
}
