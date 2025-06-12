<?php

namespace App\Http\Controllers;

use App\Exports\PeminjamanExport;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
class LaporanPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $status     = $request->status;
        $startDate  = $request->start_date;
        $endDate    = $request->end_date;
        
        $peminjamans = Peminjaman::with(['user', 'buku', 'disetujui', 'dibatalkan_oleh'])
            ->when($status, function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($startDate && $endDate, function($query) use ($startDate, $endDate) {
                return $query->whereBetween('tanggal_pinjam', [$startDate, $endDate]);
            })
            ->orderBy('tanggal_pinjam', 'desc')
             ->paginate(10);
            
        return view('laporan.peminjaman.index', compact('peminjamans'));
    }
    
    public function exportPDF(Request $request)
    {
        $data = $this->getReportData($request);
        $html = View::make('laporan.peminjaman.pdf', $data)->render();
        
        // Buat instance mPDF
        $mpdf = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4-L',
            'margin_left'   => 10,
            'margin_right'  => 10,
            'margin_top'    => 15,
            'margin_bottom' => 20,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        
        
        // Tulis konten HTML
        $mpdf->WriteHTML($html);
        
        // Output PDF
        return response()->streamDownload(function() use ($mpdf) {
            $mpdf->Output();
        }, 'laporan_peminjaman_'.now()->format('YmdHis').'.pdf');
      
    }
    
    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);
        
        return Excel::download(new PeminjamanExport($data), 'laporan_peminjaman_'.now()->format('YmdHis').'.xlsx');
    }
    
    private function getReportData($request)
    {
        $status     = $request->status;
        $startDate  = $request->start_date;
        $endDate    = $request->end_date;
        
        $peminjamans = Peminjaman::with(['user', 'buku', 'disetujui', 'dibatalkan_oleh'])
            ->when($status, function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($startDate && $endDate, function($query) use ($startDate, $endDate) {
                return $query->whereBetween('tanggal_pinjam', [$startDate, $endDate]);
            })
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();
            
        $totalDenda       = $peminjamans->sum('denda');
        $totalPeminjaman  = $peminjamans->count();
        
        return [
            'peminjamans'     => $peminjamans,
            'totalDenda'      => $totalDenda,
            'totalPeminjaman' => $totalPeminjaman,
            'startDate'       => $startDate,
            'endDate'         => $endDate,
            'status'          => $status,
            'tanggalLaporan'  => now()->format('d F Y')
        ];
    }
}