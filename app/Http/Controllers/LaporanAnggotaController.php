<?php

namespace App\Http\Controllers;

use App\Exports\AnggotaExport;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class LaporanAnggotaController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->role;
        $prodi_id = $request->prodi_id;
        $search = $request->search;
        
        $users = User::with('prodi')
            ->whereIn('role', ['dosen', 'mahasiswa'])
            ->when($role, function($query) use ($role) {
                return $query->where('role', $role);
            })
            ->when($prodi_id, function($query) use ($prodi_id) {
                return $query->where('prodi_id', $prodi_id);
            })
            ->when($search, function($query) use ($search) {
                return $query->where('nama_lengkap', 'like', '%'.$search.'%')
                            ->orWhere('npm', 'like', '%'.$search.'%')
                            ->orWhere('nidn', 'like', '%'.$search.'%');
            })
            ->orderBy('role')
            ->orderBy('nama_lengkap')
            ->paginate(10);
            
        $prodis = \App\Models\Prodi::all();
        
        return view('laporan.anggota.index', compact('users', 'prodis'));
    }
    
    public function exportPDF(Request $request)
    {
        $data = $this->getReportData($request);
        $html = View::make('laporan.anggota.pdf', $data)->render();
        
        // Buat instance mPDF
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
        
        // Tulis konten HTML
        $mpdf->WriteHTML($html);
        
        // Output PDF
        return response()->streamDownload(function() use ($mpdf) {
            $mpdf->Output();
        }, 'laporan_anggota_'.now()->format('YmdHis').'.pdf');
    }
    
    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);
        
        return Excel::download(new AnggotaExport($data), 'laporan_anggota_'.now()->format('YmdHis').'.xlsx');
    }
    
    private function getReportData($request)
    {
        $role = $request->role;
        $prodi_id = $request->prodi_id;
        $search = $request->search;
        
        $users = User::with('prodi')
            ->whereIn('role', ['dosen', 'mahasiswa'])
            ->when($role, function($query) use ($role) {
                return $query->where('role', $role);
            })
            ->when($prodi_id, function($query) use ($prodi_id) {
                return $query->where('prodi_id', $prodi_id);
            })
            ->when($search, function($query) use ($search) {
                return $query->where('nama_lengkap', 'like', '%'.$search.'%')
                            ->orWhere('npm', 'like', '%'.$search.'%')
                            ->orWhere('nidn', 'like', '%'.$search.'%');
            })
            ->orderBy('role')
            ->orderBy('nama_lengkap')
            ->get();
            
        $totalDosen = $users->where('role', 'dosen')->count();
        $totalMahasiswa = $users->where('role', 'mahasiswa')->count();
        
        return [
            'users' => $users,
            'totalDosen' => $totalDosen,
            'totalMahasiswa' => $totalMahasiswa,
            'role' => $role,
            'prodi_id' => $prodi_id,
            'search' => $search,
            'tanggalLaporan' => now()->format('d F Y')
        ];
    }
}