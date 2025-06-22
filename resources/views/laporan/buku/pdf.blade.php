<!DOCTYPE html>
<html>
<head>
    <title>Laporan Buku Perpustakaan</title>
    <style>
        body { 
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header { 
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }
        .header img {
            height: 60px;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #2c3e50;
            color: white;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
            font-size: 8px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .stat-container {
            display: flex;
            margin-bottom: 15px;
        }
        .stat-card {
            flex: 1;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            padding: 8px;
            margin: 0 5px;
            text-align: center;
        }
        .stat-title {
            font-size: 10px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #3498db;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 2px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        @if(file_exists(public_path('images/logo_perpus.png')))
            <img src="{{ public_path('images/logo_perpus.png') }}" alt="Logo Perpustakaan">
        @endif
        <h2 style="margin:5px 0;font-size:14px;">LAPORAN DATA BUKU PERPUSTAKAAN</h2>
        <h3 style="margin:5px 0;font-size:12px;">{{ config('app.name') }}</h3>
        <p style="margin:0;font-size:10px;">Periode: {{ $tanggalLaporan }} | Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <!-- Statistics Summary -->
    <div class="stat-container">
        <div class="stat-card">
            <div class="stat-title">Total Koleksi</div>
            <div class="stat-value">{{ $totalKoleksi }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Tersedia</div>
            <div class="stat-value">{{ $totalTersedia }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Dipinjam</div>
            <div class="stat-value">{{ $totalDipinjam }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Habis</div>
            <div class="stat-value">{{ $totalHabis }}</div>
        </div>
    </div>

    <!-- Main Book Data -->
    <div class="section-title">DAFTAR BUKU</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Judul</th>
                <th width="15%">Penulis</th>
                <th width="10%">ISBN</th>
                <th width="15%">Kategori</th>
                <th width="10%">Penerbit</th>
                <th width="8%">Tahun</th>
                <th width="7%">Stok</th>
                <th width="10%">Status</th>
                <th width="10%">Dipinjam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buku as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->penulis }}</td>
                    <td>{{ $item->isbn ?? '-' }}</td>
                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                    <td>{{ $item->penerbit->nama ?? '-' }}</td>
                    <td>{{ $item->tahun_terbit }}</td>
                    <td style="text-align:center">{{ $item->jumlah }}</td>
                    <td style="text-align:center">
                        @if($item->jumlah > 0)
                            <span class="badge badge-success">Tersedia</span>
                        @else
                            <span class="badge badge-danger">Habis</span>
                        @endif
                    </td>
                    <td style="text-align:center">{{ $item->peminjaman_count ?? 0 }}x</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Category Statistics -->
    <div class="section-title">STATISTIK PER KATEGORI</div>
    <table>
        <thead>
            <tr>
                <th width="60%">Kategori</th>
                <th width="20%" style="text-align:center">Jumlah Buku</th>
                <th width="20%" style="text-align:center">Total Pinjam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($totalByKategori as $kategori)
                <tr>
                    <td>{{ $kategori['nama'] }}</td>
                    <td style="text-align:center">{{ $kategori['total_buku'] }}</td>
                    <td style="text-align:center">{{ $kategori['total_pinjam'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Popular Books -->
    @if(isset($bukuPopuler) && count($bukuPopuler) > 0)
    <div class="section-title">BUKU TERPOPULER</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Judul</th>
                <th width="20%">Penulis</th>
                <th width="15%">Kategori</th>
                <th width="15%">Tahun</th>
                <th width="10%" style="text-align:center">Dipinjam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bukuPopuler as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->penulis }}</td>
                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                    <td>{{ $item->tahun_terbit }}</td>
                    <td style="text-align:center">{{ $item->peminjaman_count }}x</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Dicetak oleh: {{ Auth::user()->name ?? 'System' }} | &copy; {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</body>
</html>