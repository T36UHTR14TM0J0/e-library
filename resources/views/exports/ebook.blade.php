<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $judulLaporan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .bg-gray {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .status-available {
            color: #28a745;
            font-weight: bold;
        }
        .status-unavailable {
            color: #dc3545;
            font-weight: bold;
        }
        h2 {
            margin: 5px 0;
        }
        h3 {
            margin: 10px 0;
        }
        h6 {
            margin: 5px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <td colspan="8" style="font-weight: bold; text-align: center; border:1px solid black;height:70px;">
                    <h2>{{ $judulLaporan }}</h2>
                    <h2>{{ $institution }}</h2>
                    <h6>Periode: {{ $tanggalLaporan }} | Dicetak pada: {{ $printed_at }} oleh {{ $printed_by }}</h6>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="8" style="border: none; height: 15px;"></td>
            </tr>
            <tr>
                <td colspan="7" style="font-weight: bold; border:1px solid black;">Total Koleksi</td>
                <td style="text-align: center; border:1px solid black;">{{ $totalKoleksi }}</td>
            </tr>
            <tr>
                <td colspan="7" style="font-weight: bold; border:1px solid black;">Dapat Diunduh</td>
                <td style="text-align: center; border:1px solid black;">{{ $totalDapatDiunduh }}</td>
            </tr>
            <tr>
                <td colspan="7" style="font-weight: bold; border:1px solid black;">Tidak Dapat Diunduh</td>
                <td style="text-align: center; border:1px solid black;">{{ $totalTidakDapatDiunduh }}</td>
            </tr>
            <tr>
                <td colspan="7" style="font-weight: bold; border:1px solid black;">Total Dibaca</td>
                <td style="text-align: center; border:1px solid black;">{{ $totalDibaca }}</td>
            </tr>
            <tr>
                <td colspan="8" style="border: none; height: 15px;"></td>
            </tr>
            <tr>
                <td colspan="8" style="text-align: center; font-weight: bold; border:1px solid black;">
                     <h3>DAFTAR EBOOK</h3>
                </td>
            </tr>
            <tr>
                <th style="width: 20px; border:1px solid black;font-weight: bold;text-align:center;">No</th>
                <th style="width: 150px; border:1px solid black;font-weight: bold;text-align:center;">Judul</th>
                <th style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Penulis</th>
                <th style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Kategori</th>
                <th style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Prodi</th>
                <th style="width: 100px; border:1px solid black;font-weight: bold;text-align:center;">Penerbit</th>
                <th style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Dibaca</th>
                <th style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Status</th>
            </tr>
             @foreach($data as $key => $item)
            <tr @if($loop->even) class="bg-gray" @endif>
                <td class="text-center" style="border:1px solid black;">{{ $key + 1 }}</td>
                <td style="border:1px solid black;">{{ $item->judul }}</td>
                <td style="border:1px solid black;">{{ $item->penulis ?? '-' }}</td>
                <td style="border:1px solid black;">{{ $item->kategori->nama ?? '-' }}</td>
                <td style="border:1px solid black;">{{ $item->prodi->nama ?? '-' }}</td>
                <td style="border:1px solid black;">{{ $item->penerbit->nama ?? '-' }}</td>
                <td class="text-center" style="border:1px solid black;">{{ $item->total_dibaca ?? 0 }}x</td>
                <td class="{{ $item->dapat_diunduh ? 'status-available' : 'status-unavailable' }}" style="border:1px solid black;">
                    {{ $item->dapat_diunduh ? 'Tersedia' : 'Tidak Tersedia' }}
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="8" style="border: none; height: 15px;"></td>
            </tr>
            <tr>
                <td colspan="8" style="text-align: center; font-weight: bold; border:1px solid black;">
                     <h3>STATISTIK PER KATEGORI</h3>
                </td>
            </tr>
            <tr>
                <th colspan="4" style="font-weight: bold; text-align:center; border:1px solid black;">KATEGORI</th>
                <th colspan="4" style="font-weight: bold; text-align:center; border:1px solid black;">JUMLAH EBOOK</th>
            </tr>
            @foreach($totalByKategori as $kategori)
            <tr>
                <td colspan="4" style="text-align:center; border:1px solid black;">{{ $kategori['nama'] ?? '-' }}</td>
                <td colspan="4" style="text-align:center; border:1px solid black;">{{ $kategori['total_ebook'] ?? 0 }}</td>
            </tr>
            @endforeach
            
            @if(!empty($totalByProdi))
            <tr>
                <td colspan="8" style="border: none; height: 15px;"></td>
            </tr>
            <tr>
                <td colspan="8" style="text-align: center; font-weight: bold; border:1px solid black;">
                     <h3>STATISTIK PER PRODI</h3>
                </td>
            </tr>
            <tr>
                <th colspan="4" style="font-weight: bold; text-align:center; border:1px solid black;">PROGRAM STUDI</th>
                <th colspan="4" style="font-weight: bold; text-align:center; border:1px solid black;">JUMLAH EBOOK</th>
            </tr>
            @foreach($totalByProdi as $prodi)
            <tr>
                <td colspan="4" style="text-align:center; border:1px solid black;">{{ $prodi['nama'] ?? '-' }}</td>
                <td colspan="4" style="text-align:center; border:1px solid black;">{{ $prodi['total_ebook'] ?? 0 }}</td>
            </tr>
            @endforeach
            @endif
            
            @if(!empty($ebookPopuler))
            <tr>
                <td colspan="8" style="border: none; height: 15px;"></td>
            </tr>
            <tr>
                <td colspan="8" style="text-align: center; font-weight: bold; border:1px solid black;">
                     <h3>EBOOK TERPOPULER</h3>
                </td>
            </tr>
            <tr>
                <th style="width: 20px; border:1px solid black;font-weight: bold;text-align:center;">No</th>
                <th colspan="2" style="width: 150px; border:1px solid black;font-weight: bold;text-align:center;">Judul</th>
                <th colspan="1" style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Penulis</th>
                <th colspan="1" style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Kategori</th>
                <th colspan="1" style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Prodi</th>
                <th colspan="2" style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Total Dibaca</th>
            </tr>
            @foreach($ebookPopuler as $key => $item)
            <tr @if($loop->even) class="bg-gray" @endif>
                <td class="text-center" style="border:1px solid black;">{{ $key + 1 }}</td>
                <td colspan="2" style="border:1px solid black;">{{ $item->judul }}</td>
                <td colspan="1" style="border:1px solid black;">{{ $item->penulis ?? '-' }}</td>
                <td colspan="1" style="border:1px solid black;">{{ $item->kategori->nama ?? '-' }}</td>
                <td colspan="1" style="border:1px solid black;">{{ $item->prodi->nama ?? '-' }}</td>
                <td colspan="2" class="text-center" style="border:1px solid black;">{{ $item->readings_count }}x</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</body>
</html>