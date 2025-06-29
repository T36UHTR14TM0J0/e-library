<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class EbookExport implements FromView
{
    protected $data;
    protected $judulLaporan;
    protected $tanggalLaporan;
    protected $totalKoleksi;
    protected $totalDapatDiunduh;
    protected $totalTidakDapatDiunduh;
    protected $totalDibaca;
    protected $totalByKategori;
    protected $totalByProdi;
    protected $ebookPopuler;

    public function __construct(array $data, string $tanggalLaporan = null)
    {
        $this->data = $data;
        $this->judulLaporan = 'LAPORAN DATA EBOOK PERPUSTAKAAN';
        $this->tanggalLaporan = $tanggalLaporan ?? now()->format('d F Y');
        $this->totalKoleksi = $data['totalKoleksi'] ?? 0;
        $this->totalDapatDiunduh = $data['totalDapatDiunduh'] ?? 0;
        $this->totalTidakDapatDiunduh = $data['totalTidakDapatDiunduh'] ?? 0;
        $this->totalDibaca = $data['totalDibaca'] ?? 0;
        $this->totalByKategori = $data['totalByKategori'] ?? [];
        $this->totalByProdi = $data['totalByProdi'] ?? [];
        $this->ebookPopuler = $data['ebookPopuler'] ?? [];
    }

    public function view(): View
    {
        return view('exports.ebook', [
            'data' => $this->data['ebooks'] ?? [],
            'judulLaporan' => $this->judulLaporan,
            'tanggalLaporan' => $this->tanggalLaporan,
            'totalKoleksi' => $this->totalKoleksi,
            'totalDapatDiunduh' => $this->totalDapatDiunduh,
            'totalTidakDapatDiunduh' => $this->totalTidakDapatDiunduh,
            'totalDibaca' => $this->totalDibaca,
            'totalByKategori' => $this->totalByKategori,
            'totalByProdi' => $this->totalByProdi,
            'ebookPopuler' => $this->ebookPopuler,
            'printed_by' => auth()->user()->name ?? 'System',
            'printed_at' => now()->format('d/m/Y H:i'),
            'institution' => config('app.name', 'E-Library')
        ]);
    }
}