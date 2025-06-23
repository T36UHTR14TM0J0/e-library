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
        .status-tersedia {
            color: #28a745;
            font-weight: bold;
        }
        .status-habis {
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
                <td colspan="10" style="font-weight: bold; text-align: center; border:1px solid black;height:70px;">
                    <h2>{{ $judulLaporan }}</h2>
                    <h2>{{ config('app.name') }}</h2>
                    <h6>Periode: {{ $tanggalLaporan }} | Dicetak pada: {{ now()->format('d F Y H:i') }}</h6>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="10" style="border: none; height: 15px;"></td>
            </tr>
            <tr>
                <td colspan="9" style="font-weight: bold; border:1px solid black;">Total Koleksi</td>
                <td style="text-align: center; border:1px solid black;">{{ $totalKoleksi }}</td>
            </tr>
            <tr>
                <td colspan="9" style="font-weight: bold; border:1px solid black;">Tersedia</td>
                <td style="text-align: center; border:1px solid black;">{{ $totalTersedia }}</td>
            </tr>
            <tr>
                <td colspan="9" style="font-weight: bold; border:1px solid black;">Dipinjam</td>
                <td style="text-align: center; border:1px solid black;">{{ $totalDipinjam }}</td>
            </tr>
            <tr>
                <td colspan="9" style="font-weight: bold; border:1px solid black;">Habis</td>
                <td style="text-align: center; border:1px solid black;">{{ $totalHabis }}</td>
            </tr>
            <tr>
                <td colspan="10" style="border: none; height: 15px;"></td>
            </tr>
            <tr>
                <td colspan="10" style="text-align: center; font-weight: bold; border:1px solid black;">
                     <h3>DAFTAR BUKU</h3>
                </td>
            </tr>
            <tr>
                <th style="width: 20px; border:1px solid black;font-weight: bold;text-align:center;">No</th>
                <th style="width: 150px; border:1px solid black;font-weight: bold;text-align:center;">Judul Buku</th>
                <th style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Penulis</th>
                <th style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">ISBN</th>
                <th style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Kategori</th>
                <th style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Penerbit</th>
                <th style="width: 100px; border:1px solid black;font-weight: bold;text-align:center;">Tahun Terbit</th>
                <th style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Stok</th>
                <th style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Status</th>
                <th style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Dipinjam</th>
            </tr>
             @foreach($data['buku'] as $key => $item)
            <tr @if($loop->even) class="bg-gray" @endif>
                <td class="text-center" style="border:1px solid black;">{{ $key + 1 }}</td>
                <td style="border:1px solid black;">{{ $item->judul }}</td>
                <td style="border:1px solid black;">{{ $item->penulis ?? '-' }}</td>
                <td style="border:1px solid black;">{{ $item->isbn ?? '-' }}</td>
                <td style="border:1px solid black;">{{ $item->kategori->nama ?? '-' }}</td>
                <td style="border:1px solid black;">{{ $item->penerbit->nama ?? '-' }}</td>
                <td class="text-center" style="border:1px solid black;">{{ $item->tahun_terbit }}</td>
                <td class="text-center" style="border:1px solid black;">{{ $item->jumlah }}</td>
                <td class="{{ $item->jumlah > 0 ? 'status-tersedia' : 'status-habis' }}" style="border:1px solid black;">
                    {{ $item->jumlah > 0 ? 'Tersedia' : 'Habis' }}
                </td>
                <td class="text-center" style="border:1px solid black;">{{ $item->peminjaman_count ?? 0 }}x</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="10" style="border: none; height: 15px;"></td>
            </tr>
            <tr>
                <td colspan="10" style="text-align: center; font-weight: bold; border:1px solid black;">
                     <h3>STATISTIK PER KATEGORI</h3>
                </td>
            </tr>
            <tr>
                <th colspan="5" style="font-weight: bold; text-align:center; border:1px solid black;">KATEGORI</th>
                <th colspan="5" style="font-weight: bold; text-align:center; border:1px solid black;">JUMLAH BUKU</th>
            </tr>
            @foreach($totalByKategori as $kategori)
            <tr>
                <td colspan="5" style="text-align:center; border:1px solid black;">{{ $kategori['nama'] ?? '-' }}</td>
                <td colspan="5" style="text-align:center; border:1px solid black;">{{ $kategori['total_buku'] ?? 0 }}</td>
            </tr>
            @endforeach
            @if(!empty($bukuPopuler))
            <tr>
                <td colspan="10" style="border: none; height: 15px;"></td>
            </tr>
            <tr>
                <td colspan="10" style="text-align: center; font-weight: bold; border:1px solid black;">
                     <h3>BUKU TERPOPULER</h3>
                </td>
            </tr>
            <tr>
                <th style="width: 20px; border:1px solid black;font-weight: bold;text-align:center;">No</th>
                <th colspan="3" style="width: 150px; border:1px solid black;font-weight: bold;text-align:center;">Judul</th>
                <th colspan="2" style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Penulis</th>
                <th colspan="2" style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Kategori</th>
                <th style="width: 100px; border:1px solid black;font-weight: bold;text-align:center;">Tahun</th>
                <th style="width: 120px; border:1px solid black;font-weight: bold;text-align:center;">Dipinjam</th>
            </tr>
            @foreach($bukuPopuler as $key => $item)
            <tr @if($loop->even) class="bg-gray" @endif>
                <td class="text-center" style="border:1px solid black;">{{ $key + 1 }}</td>
                <td colspan="3" style="border:1px solid black;">{{ $item->judul }}</td>
                <td colspan="2" style="border:1px solid black;">{{ $item->penulis ?? '-' }}</td>
                <td colspan="2" style="border:1px solid black;">{{ $item->kategori->nama ?? '-' }}</td>
                <td class="text-center" style="border:1px solid black;">{{ $item->tahun_terbit }}</td>
                <td class="text-center" style="border:1px solid black;">{{ $item->peminjaman_count }}x</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</body>
</html>