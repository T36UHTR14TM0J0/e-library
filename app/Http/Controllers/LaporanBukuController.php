<?php

namespace App\Http\Controllers;

use App\Exports\BukuExport;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class LaporanBukuController extends Controller
{
    public function index(Request $request)
    {
        $kategori_id = $request->kategori_id;
        $status      = $request->status; // 'tersedia', 'habis', 'rusak', 'dipinjam'
        $search       = $request->search;
        
        $buku = Buku::with(['kategori', 'penerbit'])
            ->withCount(['peminjaman' => function($query) {
                $query->where('status', 'dipinjam');
            }])
            ->when($kategori_id, function($query) use ($kategori_id) {
                return $query->where('kategori_id', $kategori_id);
            })
            ->when($status, function($query) use ($status) {
                if ($status == 'habis') {
                    return $query->where('jumlah', 0);
                } elseif ($status == 'tersedia') {
                    return $query->where('jumlah', '>', 0);
                } elseif ($status == 'dipinjam') {
                    return $query->whereHas('peminjaman', function($q) {
                        $q->where('status', 'dipinjam');
                    });
                }
            })
            ->when($search, function($query) use ($search) {
                return $query->where('judul', 'like', '%'.$search.'%')
                            ->orWhere('isbn', 'like', '%'.$search.'%')
                            ->orWhere('penerbit', 'like', '%'.$search.'%');
            })
            ->orderBy('judul')
            ->paginate(10);
            
        // Hitung statistik
        $totalTersedia  = Buku::where('jumlah', '>', 0)->count();
        $totalHabis     = Buku::where('jumlah', 0)->count();
        $totalDipinjam  = Buku::whereHas('peminjaman', function($q) {
            $q->where('status', 'dipinjam');
        })->count();
        
        $kategoris = Kategori::all();
        
        return view('laporan.buku.index', compact(
            'buku', 
            'kategoris',
            'totalTersedia',
            'totalHabis',
            'totalDipinjam'
        ));
    }
    
    public function exportPDF(Request $request)
    {
        $data = $this->getReportData($request);
        $html = View::make('laporan.buku.pdf', $data)->render();
        
        $mpdf = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'margin_left'   => 10,
            'margin_right'  => 10,
            'margin_top'    => 15,
            'margin_bottom' => 20,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);
        
        $mpdf->WriteHTML($html);
        
        return response()->streamDownload(function() use ($mpdf) {
            $mpdf->Output();
        }, 'laporan_buku_'.now()->format('YmdHis').'.pdf');
    }
    
    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);
        
        return Excel::download(new BukuExport($data), 'laporan_buku_'.now()->format('YmdHis').'.xlsx');
    }
    
    private function getReportData($request)
    {
        $kategori_id = $request->kategori_id;
        $status = $request->status;
        $search = $request->search;
        
        $buku = Buku::with(['kategori','penerbit'])
            ->when($kategori_id, function($query) use ($kategori_id) {
                return $query->where('kategori_id', $kategori_id);
            })
            ->when($status, function($query) use ($status) {
                if ($status == 'habis') {
                    return $query->where('jumlah', 0);
                } elseif ($status == 'tersedia') {
                    return $query->where('jumlah', '>', 0);
                } 
            })
            ->when($search, function($query) use ($search) {
                return $query->where('judul', 'like', '%'.$search.'%')
                            ->orWhere('isbn', 'like', '%'.$search.'%')
                            ->orWhere('penerbit', 'like', '%'.$search.'%');
            })
            ->orderBy('judul')
            ->get();
            
        $totalTersedia = $buku->where('jumlah', '>', 0)->count();
        $totalHabis = $buku->where('jumlah', 0)->count();
        
        return [
            'buku'            => $buku,
            'totalTersedia'   => $totalTersedia,
            'totalHabis'      => $totalHabis,
            'kategori_id'     => $kategori_id,
            'status'          => $status,
            'search'          => $search,
            'tanggalLaporan'  => now()->format('d F Y')
        ];
    }
}