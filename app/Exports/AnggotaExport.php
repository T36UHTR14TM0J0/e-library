<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AnggotaExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data['users'];
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Role',
            'NPM/NIDN',
            'Program Studi',
            'Email',
            'Username'
        ];
    }

    public function map($user): array
    {
        return [
            $user->nama_lengkap,
            ucfirst($user->role),
            $user->role == 'mahasiswa' ? $user->npm : $user->nidn,
            $user->prodi ? $user->prodi->nama : '-',
            $user->email,
            $user->username
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,  // Nama Lengkap
            'B' => 15,  // Role
            'C' => 20,  // NPM/NIDN
            'D' => 25,  // Program Studi
            'E' => 30,  // Email
            'F' => 20,  // Username
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
                    'color' => ['rgb' => '3490dc'] // Warna biru
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            
            // Style untuk seluruh data
            'A2:F'.$sheet->getHighestRow() => [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ],
            
            // Alignment khusus untuk kolom tertentu
            'B' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            'C' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            'F' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            
            // Border untuk seluruh tabel
            'A1:F'.$sheet->getHighestRow() => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]
        ];
    }
}