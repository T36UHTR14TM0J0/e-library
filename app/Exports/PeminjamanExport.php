<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        return $this->data['peminjamans'];
    }
    
    public function headings(): array
    {
        return [
            'No',
            'Peminjam',
            'Buku',
            'Tanggal Pinjam',
            'Jatuh Tempo',
            'Tanggal Kembali',
            'Status',
            'Denda (Rp)',
            'Disetujui Oleh'
        ];
    }
    
    public function map($peminjaman): array
    {
        return [
            $peminjaman->id,
            $peminjaman->user->nama_lengkap,
            $peminjaman->buku ? $peminjaman->buku->judul : 'Buku dihapus',
            $peminjaman->tanggal_pinjam->format('d/m/Y'),
            $peminjaman->tanggal_jatuh_tempo->format('d/m/Y'),
            $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d/m/Y') : '-',
            ucfirst($peminjaman->status),
            $peminjaman->denda,
            $peminjaman->disetujui ? $peminjaman->disetujui->username : '-'
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Set default font
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Arial');
        
        // Set column widths (in case auto-size is disabled)
        $sheet->getColumnDimension('A')->setWidth(8);  // No
        $sheet->getColumnDimension('B')->setWidth(25); // Peminjam
        $sheet->getColumnDimension('C')->setWidth(35); // Buku
        $sheet->getColumnDimension('D')->setWidth(15); // Tanggal Pinjam
        $sheet->getColumnDimension('E')->setWidth(15); // Jatuh Tempo
        $sheet->getColumnDimension('F')->setWidth(15); // Tanggal Kembali
        $sheet->getColumnDimension('G')->setWidth(15); // Status
        $sheet->getColumnDimension('H')->setWidth(15); // Denda
        $sheet->getColumnDimension('I')->setWidth(20); // Disetujui Oleh
        
        // Format Denda column as currency
        $sheet->getStyle('H2:H' . ($sheet->getHighestRow()))
              ->getNumberFormat()
              ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        
        // Header style
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3490dc']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        
        // Data style
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        
        // Apply styles
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);
        $sheet->getStyle('A2:I' . ($sheet->getHighestRow()))->applyFromArray($dataStyle);
        
        // Specific column alignments
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H:H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        // Freeze header row
        $sheet->freezePane('A2');
        
        // Auto-filter
        $sheet->setAutoFilter('A1:I1');
        
        return [];
    }
}