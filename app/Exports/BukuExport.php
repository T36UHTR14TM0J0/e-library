<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class BukuExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles, WithTitle, WithCustomStartCell, WithEvents
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
        $this->judulLaporan = 'LAPORAN DATA BUKU PERPUSTAKAAN';
        $this->tanggalLaporan = $data['tanggalLaporan'] ?? now()->format('d F Y');
        $this->totalKoleksi = $data['totalKoleksi'] ?? 0;
        $this->totalTersedia = $data['totalTersedia'] ?? 0;
        $this->totalDipinjam = $data['totalDipinjam'] ?? 0;
        $this->totalHabis = $data['totalHabis'] ?? 0;
        $this->totalByKategori = $data['totalByKategori'] ?? [];
        $this->bukuPopuler = $data['bukuPopuler'] ?? [];
    }

    public function collection()
    {
        return collect($this->data['buku'])->map(function($item, $key) {
            return [
                'No' => $key + 1,
                'Judul' => $item->judul,
                'Penulis' => $item->penulis ?? '-',
                'ISBN' => $item->isbn ?? '-',
                'Kategori' => $item->kategori->nama ?? '-',
                'Penerbit' => $item->penerbit->nama ?? '-',
                'Tahun' => $item->tahun_terbit,
                'Stok' => $item->jumlah,
                'Status' => $item->jumlah > 0 ? 'Tersedia' : 'Habis',
                'Dipinjam' => ($item->peminjaman_count ?? 0) . 'x'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul Buku',
            'Penulis',
            'ISBN',
            'Kategori',
            'Penerbit',
            'Tahun Terbit',
            'Stok',
            'Status',
            'Dipinjam'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,    // No
            'B' => 35,   // Judul
            'C' => 20,   // Penulis
            'D' => 15,   // ISBN
            'E' => 20,   // Kategori
            'F' => 20,   // Penerbit
            'G' => 8,    // Tahun
            'H' => 8,    // Stok
            'I' => 12,   // Status
            'J' => 10    // Dipinjam
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header Laporan
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', $this->judulLaporan);
        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', config('app.name'));
        $sheet->mergeCells('A3:J3');
        $sheet->setCellValue('A3', 'Periode: ' . $this->tanggalLaporan . ' | Dicetak pada: ' . now()->format('d F Y H:i'));

        // Style Header
        $sheet->getStyle('A1:J3')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A1')->getFont()->setSize(14);
        $sheet->getStyle('A2')->getFont()->setSize(12);
        $sheet->getStyle('A3')->getFont()->setSize(10);

        // Statistics Summary
        $sheet->setCellValue('A5', 'Total Koleksi');
        $sheet->setCellValue('B5', $this->totalKoleksi);
        $sheet->setCellValue('D5', 'Tersedia');
        $sheet->setCellValue('E5', $this->totalTersedia);
        $sheet->setCellValue('G5', 'Dipinjam');
        $sheet->setCellValue('H5', $this->totalDipinjam);
        $sheet->setCellValue('J5', 'Habis');
        $sheet->setCellValue('K5', $this->totalHabis);

        // Style Statistics
        $sheet->getStyle('A5:K5')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        $sheet->getStyle('B5')->getFont()->setSize(18)->setColor(new Color(Color::COLOR_BLUE));
        $sheet->getStyle('E5')->getFont()->setSize(18)->setColor(new Color(Color::COLOR_BLUE));
        $sheet->getStyle('H5')->getFont()->setSize(18)->setColor(new Color(Color::COLOR_BLUE));
        $sheet->getStyle('K5')->getFont()->setSize(18)->setColor(new Color(Color::COLOR_BLUE));

        // Section Title - Daftar Buku
        $sheet->setCellValue('A7', 'DAFTAR BUKU');
        $sheet->mergeCells('A7:J7');
        $sheet->getStyle('A7')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => '2c3e50']
            ],
        ]);

        // Table Header
        $sheet->fromArray($this->headings(), null, 'A8');
        $sheet->getStyle('A8:J8')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '2c3e50']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set wrap text for long content
        $sheet->getStyle('B9:B' . (count($this->data['buku']) + 8))
            ->getAlignment()
            ->setWrapText(true);

        // Set border for data
        $lastRow = count($this->data['buku']) + 8;
        $sheet->getStyle('A8:J' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);

        // Alternating row colors
        for ($i = 9; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':J' . $i)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('F9F9F9');
            }
        }

        // Alignment
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A8:J8')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B9:B' . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        // Status styling
        $statusColumn = 'I';
        for ($i = 9; $i <= $lastRow; $i++) {
            $status = $sheet->getCell($statusColumn . $i)->getValue();
            if ($status === 'Tersedia') {
                $sheet->getStyle($statusColumn . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D4EDDA']
                    ],
                    'font' => [
                        'color' => ['rgb' => '155724'],
                        'bold' => true
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ]
                ]);
            } else {
                $sheet->getStyle($statusColumn . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'F8D7DA']
                    ],
                    'font' => [
                        'color' => ['rgb' => '721C24'],
                        'bold' => true
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ]
                ]);
            }
        }

        // Set height for header row
        $sheet->getRowDimension(8)->setRowHeight(20);

        return [];
    }

    public function title(): string
    {
        return 'Data Buku';
    }

    public function startCell(): string
    {
        return 'A8';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastBookRow = count($this->data['buku']) + 8;
                
                // STATISTIK PER KATEGORI - VERSI IMPROVED
                $startCategoryRow = $lastBookRow + 3; // Tambah jarak
                
                // Judul Section
                $sheet->setCellValue('A' . $startCategoryRow, 'STATISTIK PER KATEGORI');
                $sheet->mergeCells('A' . $startCategoryRow . ':B' . $startCategoryRow);
                $sheet->getStyle('A' . $startCategoryRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => '2c3e50']
                    ],
                ]);

                // Header Tabel
                $sheet->setCellValue('A' . ($startCategoryRow + 1), 'KATEGORI');
                $sheet->setCellValue('B' . ($startCategoryRow + 1), 'JUMLAH BUKU');
                
                // Lebar kolom yang lebih proporsional
                $sheet->getColumnDimension('A')->setWidth(40);
                $sheet->getColumnDimension('B')->setWidth(20);
                
                // Style Header
                $sheet->getStyle('A' . ($startCategoryRow + 1) . ':B' . ($startCategoryRow + 1))->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '2c3e50']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Data Kategori
                $currentRow = $startCategoryRow + 2;
                foreach ($this->totalByKategori as $kategori) {
                    $sheet->setCellValue('A' . $currentRow, $kategori['nama'] ?? '-');
                    $sheet->setCellValue('B' . $currentRow, $kategori['total_buku'] ?? 0);
                    
                    // Style untuk data
                    $sheet->getStyle('A' . $currentRow . ':B' . $currentRow)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'DDDDDD'],
                            ],
                        ],
                    ]);
                    
                    // Style khusus untuk kolom jumlah (rata tengah)
                    $sheet->getStyle('B' . $currentRow)->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    
                    $currentRow++;
                }

                // Alternating row colors
                for ($i = $startCategoryRow + 2; $i < $currentRow; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle('A' . $i . ':B' . $i)
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('F9F9F9');
                    }
                }

                // Border untuk seluruh tabel
                $sheet->getStyle('A' . ($startCategoryRow + 1) . ':B' . ($currentRow - 1))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_MEDIUM);

                // BUKU TERPOPULER (jika ada)
                if (!empty($this->bukuPopuler)) {
                    $startPopularRow = $currentRow + 2;
                    
                    $sheet->setCellValue('A' . $startPopularRow, 'BUKU TERPOPULER');
                    $sheet->mergeCells('A' . $startPopularRow . ':F' . $startPopularRow);
                    $sheet->getStyle('A' . $startPopularRow)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 11,
                            'color' => ['rgb' => '2c3e50']
                        ],
                    ]);

                    // Popular Books Header
                    $sheet->setCellValue('A' . ($startPopularRow + 1), 'No');
                    $sheet->setCellValue('B' . ($startPopularRow + 1), 'Judul');
                    $sheet->setCellValue('C' . ($startPopularRow + 1), 'Penulis');
                    $sheet->setCellValue('D' . ($startPopularRow + 1), 'Kategori');
                    $sheet->setCellValue('E' . ($startPopularRow + 1), 'Tahun');
                    $sheet->setCellValue('F' . ($startPopularRow + 1), 'Dipinjam');
                    
                    $sheet->getStyle('A' . ($startPopularRow + 1) . ':F' . ($startPopularRow + 1))->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FFFFFF']
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => '2c3e50']
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    // Popular Books Data
                    $popularRow = $startPopularRow + 2;
                    foreach ($this->bukuPopuler as $key => $item) {
                        $sheet->setCellValue('A' . $popularRow, $key + 1);
                        $sheet->setCellValue('B' . $popularRow, $item->judul);
                        $sheet->setCellValue('C' . $popularRow, $item->penulis ?? '-');
                        $sheet->setCellValue('D' . $popularRow, $item->kategori->nama ?? '-');
                        $sheet->setCellValue('E' . $popularRow, $item->tahun_terbit);
                        $sheet->setCellValue('F' . $popularRow, $item->peminjaman_count . 'x');
                        $popularRow++;
                    }

                    // Popular Books Style
                    $lastPopularRow = $popularRow - 1;
                    $sheet->getStyle('A' . ($startPopularRow + 1) . ':F' . $lastPopularRow)
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN);
                    
                    $sheet->getStyle('A' . ($startPopularRow + 2) . ':A' . $lastPopularRow)
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('F' . ($startPopularRow + 2) . ':F' . $lastPopularRow)
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    
                    // Alternating row colors for popular books
                    for ($i = $startPopularRow + 2; $i <= $lastPopularRow; $i++) {
                        if ($i % 2 == 0) {
                            $sheet->getStyle('A' . $i . ':F' . $i)
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('F9F9F9');
                        }
                    }

                    // Set column widths for popular books
                    $sheet->getColumnDimension('A')->setWidth(5);
                    $sheet->getColumnDimension('B')->setWidth(35);
                    $sheet->getColumnDimension('C')->setWidth(20);
                    $sheet->getColumnDimension('D')->setWidth(20);
                    $sheet->getColumnDimension('E')->setWidth(8);
                    $sheet->getColumnDimension('F')->setWidth(10);

                    // Set wrap text for judul buku populer
                    $sheet->getStyle('B' . ($startPopularRow + 2) . ':B' . $lastPopularRow)
                        ->getAlignment()
                        ->setWrapText(true);
                }

                // FOOTER
                $footerRow = (isset($lastPopularRow) ? $lastPopularRow + 2 : $currentRow + 2);
                $sheet->setCellValue('A' . $footerRow, 'Dicetak oleh: ' . (auth()->user()->name ?? 'System') . ' | © ' . date('Y') . ' ' . config('app.name'));
                $sheet->mergeCells('A' . $footerRow . ':J' . $footerRow);
                $sheet->getStyle('A' . $footerRow)->applyFromArray([
                    'font' => [
                        'size' => 8,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'DDDDDD'],
                        ],
                    ],
                ]);
            },
        ];
    }
}