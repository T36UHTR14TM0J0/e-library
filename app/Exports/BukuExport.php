<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class BukuExport implements FromView
{
    protected $data;
    protected $judulLaporan;
    protected $tanggalLaporan;
    protected $totalKoleksi;
    protected $totalTersedia;
    protected $totalDipinjam;
    protected $totalHabis;
    protected $totalByKategori;
    protected $bukuPopuler;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->judulLaporan     = 'LAPORAN DATA BUKU PERPUSTAKAAN';
        $this->tanggalLaporan   = $data['tanggalLaporan'] ?? now()->format('d F Y');
        $this->totalKoleksi     = $data['totalKoleksi'] ?? 0;
        $this->totalTersedia    = $data['totalTersedia'] ?? 0;
        $this->totalDipinjam    = $data['totalDipinjam'] ?? 0;
        $this->totalHabis       = $data['totalHabis'] ?? 0;
        $this->totalByKategori  = $data['totalByKategori'] ?? [];
        $this->bukuPopuler      = $data['bukuPopuler'] ?? [];
    }

    public function view(): View
    {
        return view('exports.buku', [
            'data'              => $this->data,
            'judulLaporan'      => $this->judulLaporan,
            'tanggalLaporan'    => $this->tanggalLaporan,
            'totalKoleksi'      => $this->totalKoleksi,
            'totalTersedia'     => $this->totalTersedia,
            'totalDipinjam'     => $this->totalDipinjam,
            'totalHabis'        => $this->totalHabis,
            'totalByKategori'   => $this->totalByKategori,
            'bukuPopuler'       => $this->bukuPopuler,
        ]);
    }
}