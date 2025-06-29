<?php

namespace App\Http\Controllers;

use App\Exports\BukuExport;
use Illuminate\Http\Request;
use App\Models\Ebook;
use App\Models\EbookReading;
use App\Models\Kategori;
use App\Models\Prodi;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Peminjaman;
use Mpdf\Mpdf;

class LaporanEbookController extends Controller
{
    
    public function index(Request $request)
    {
        // Query dasar untuk ebook dengan filter
        $ebooks = Ebook::with(['kategori', 'penerbit', 'prodi', 'pengunggah'])
            ->withCount(['readings as total_dibaca'])
            ->when($request->kategori_id, function($query, $kategori_id) {
                return $query->where('kategori_id', $kategori_id);
            })
            ->when($request->prodi_id, function($query, $prodi_id) {
                return $query->where('prodi_id', $prodi_id);
            })
            ->when($request->status, function($query, $status) {
                if ($status == 'dapat_diunduh') {
                    return $query->where('izin_unduh', true);
                } elseif ($status == 'tidak_dapat_diunduh') {
                    return $query->where('izin_unduh', false);
                }
            })
            ->when($request->search, function($query, $search) {
                return $query->where('judul', 'like', '%'.$search.'%')
                            ->orWhere('penulis', 'like', '%'.$search.'%');
            })
            ->orderBy('judul')
            ->paginate(10);

        // Statistik dasar dari tabel ebooks
        $totalKoleksi   = Ebook::count();
        $totalDapatDiunduh  = Ebook::where('izin_unduh', true)->count();
        $totalTidakDapatDiunduh = Ebook::where('izin_unduh', false)->count();
        
        // Statistik pembacaan ebook
        $totalDibaca      = EbookReading::count();
        $pembacaAktif     = EbookReading::select('user_id')
                            ->groupBy('user_id')
                            ->get()
                            ->count();
        
        // Statistik gabungan
        $totalByKategori = Kategori::withCount('ebook')
            ->get()
            ->map(function ($kategori) {
                return [
                    'nama' => $kategori->nama,
                    'total_ebook' => $kategori->ebook_count
                ];
            });
        
        $totalByProdi = Prodi::withCount('ebook')
            ->get()
            ->map(function ($prodi) {
                return [
                    'nama' => $prodi->nama,
                    'total_ebook' => $prodi->ebook_count
                ];
            });
        
        $ebookBaru = Ebook::where('created_at', '>=', now()->subDays(30))
            ->count();
        
        $ebookPopuler = Ebook::withCount('readings as readings_count')
            ->orderByDesc('readings_count')
            ->take(5)
            ->get();
        
        // Statistik pembacaan
        $pembacaanTerakhir = EbookReading::with(['ebook', 'user'])
            ->latest()
            ->take(5)
            ->get();
        
        $kategoris = Kategori::all();
        $prodis = Prodi::all();
        
        return view('laporan.ebook.index', compact(
            'ebooks',
            'kategoris',
            'prodis',
            'totalKoleksi',
            'totalDapatDiunduh',
            'totalTidakDapatDiunduh',
            'totalDibaca',
            'pembacaAktif',
            'totalByKategori',
            'totalByProdi',
            'ebookBaru',
            'ebookPopuler',
            'pembacaanTerakhir'
        ));
    }
    
    public function exportPDF(Request $request)
    {
        try {
            $data = $this->getEnhancedReportData($request);
            
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 25,
                'margin_bottom' => 20,
                'margin_header' => 10,
                'margin_footer' => 10,
                'default_font_size' => 10,
                'orientation' => 'L'
            ]);

            // Set document metadata
            $mpdf->SetTitle('Laporan Buku - ' . $data['tanggalLaporan']);
            $mpdf->SetAuthor($data['printed_by']);
            $mpdf->SetCreator($data['institution']);

            // Header
            $mpdf->SetHTMLHeader('
            <div style="text-align:center;border-bottom:1px solid #ddd;padding-bottom:5px;">
                <h3 style="margin:0;color:#2c3e50;">LAPORAN DATA BUKU</h3>
                <p style="margin:5px 0 0;font-size:12px;">'.$data['institution'].' | '.$data['tanggalLaporan'].'</p>
            </div>');

            // Footer
            $mpdf->SetHTMLFooter('
            <table width="100%" style="font-size:10px;border-top:1px solid #ddd;">
                <tr>
                    <td width="33%">Halaman {PAGENO} dari {nb}</td>
                    <td width="34%" style="text-align:center">'.$data['printed_at'].'</td>
                    <td width="33%" style="text-align:right">'.$data['printed_by'].'</td>
                </tr>
            </table>');

            $html = View::make('laporan.buku.pdf', $data)->render();
            $mpdf->WriteHTML($html);

            return response()->streamDownload(
                function() use ($mpdf) {
                    $mpdf->Output('', 'I');
                },
                'Laporan_Buku_'.now()->format('Ymd_His').'.pdf',
                ['Content-Type' => 'application/pdf']
            );

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghasilkan PDF: '.$e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getEnhancedReportData($request);
        
        // Prepare data for export - transform the bukuAll collection
        $exportData = [
            'buku'                  => $data['bukuAll'],
            'totalKoleksi'          => $data['totalKoleksi'],
            'totalTersedia'         => $data['totalTersedia'],
            'totalDipinjam'         => $data['totalDipinjam'],
            'totalHabis'            => $data['totalHabis'],
            'totalByKategori'       => $data['totalByKategori'],
            'bukuPopuler'           => $data['bukuPopuler'],
            'tanggalLaporan'        => $data['tanggalLaporan']
        ];
        
        
        return Excel::download(
            new BukuExport($exportData, $data['tanggalLaporan']), 
            'laporan_buku_'.now()->format('YmdHis').'.xlsx'
        );
    }


    private function getEnhancedReportData($request)
    {
        // Main book query with filters
        $bukuQuery = Buku::with(['kategori', 'penerbit'])
            ->withCount(['peminjaman' => function($query) {
                $query->where('status', '!=', 'dibatalkan');
            }])
            ->when($request->kategori_id, function($query, $kategori_id) {
                return $query->where('kategori_id', $kategori_id);
            })
            ->when($request->status, function($query, $status) {
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
            ->when($request->search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', '%'.$search.'%')
                    ->orWhere('isbn', 'like', '%'.$search.'%')
                    ->orWhere('penulis', 'like', '%'.$search.'%');
                });
            });

        // Get paginated data for view
        $bukuPaginated = $bukuQuery->orderBy('judul')->paginate(10);
        
        // Get all data for export
        $bukuAll = $bukuQuery->orderBy('judul')->get();

        // Calculate statistics
        $totalKoleksi = $bukuAll->count();
        $totalTersedia = $bukuAll->where('jumlah', '>', 0)->count();
        $totalHabis = $bukuAll->where('jumlah', 0)->count();
        $totalDipinjam = $bukuAll->sum('peminjaman_count');
        $bukuBaru = Buku::where('created_at', '>=', now()->subDays(30))->count();

        // Category statistics
        $totalByKategori = Kategori::withCount(['buku' => function($query) {
                $query->withCount('peminjaman');
            }])
            ->get()
            ->map(function($kategori) {
                return [
                    'nama' => $kategori->nama,
                    'total_buku' => $kategori->buku_count,
                    'total_pinjam' => $kategori->buku->sum('peminjaman_count')
                ];
            });

        // Recent borrowings
        $peminjamanTerakhir = Peminjaman::with(['buku', 'user'])
            ->where('status', '!=', 'dibatalkan')
            ->latest()
            ->take(5)
            ->get();

        // Popular books
        $bukuPopuler = Buku::withCount(['peminjaman' => function($query) {
                $query->where('status', '!=', 'dibatalkan');
            }])
            ->orderBy('peminjaman_count', 'desc')
            ->take(5)
            ->get();

        return [
            // For view
            'buku' => $bukuPaginated,
            'kategoris' => Kategori::all(),
            
            // Statistics
            'totalKoleksi' => $totalKoleksi,
            'totalTersedia' => $totalTersedia,
            'totalHabis' => $totalHabis,
            'totalDipinjam' => $totalDipinjam,
            'bukuBaru' => $bukuBaru,
            'totalByKategori' => $totalByKategori,
            'peminjamanTerakhir' => $peminjamanTerakhir,
            'bukuPopuler' => $bukuPopuler,
            
            // For export
            'bukuAll' => $bukuAll,
            'tanggalLaporan' => now()->format('d F Y'),
            'printed_by' => auth()->user()->name ?? 'System',
            'printed_at' => now()->format('d/m/Y H:i'),
            'institution' => config('app.name', 'E-Library'),
            
            // Filter parameters
            'kategori_id' => $request->kategori_id,
            'status' => $request->status,
            'search' => $request->search
        ];
    }
}