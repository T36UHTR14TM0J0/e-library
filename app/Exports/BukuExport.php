<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BukuExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data['buku'])->map(function($item) {
            return [
                'Judul' => $item->judul,
                'ISBN' => $item->isbn ?? '-',
                'Kategori' => $item->kategori->nama ?? '-',
                'Penerbit' => $item->penerbit->nama ?? '-',
                'Stok' => $item->jumlah,
                'Tersedia' => $item->jumlah - ($item->peminjaman_count ?? 0),
                'Status' => $item->jumlah > 0 ? 'Tersedia' : 'Habis'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Judul Buku',
            'ISBN',
            'Kategori',
            'Penerbit',
            'Stok Total',
            'Stok Tersedia',
            'Status'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,  // Judul Buku
            'B' => 20,   // ISBN
            'C' => 25,   // Kategori
            'D' => 25,   // Penerbit
            'E' => 15,   // Stok Total
            'F' => 15,   // Sedang Dipinjam
            'G' => 15,   // Stok Tersedia
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '3490DC']
                ]
            ],
            
            // Style untuk kolom angka
            'E:H' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                ]
            ],
            
            // Auto filter
            'A1:H1' => [
                'autoFilter' => true
            ]
        ];
    }
}