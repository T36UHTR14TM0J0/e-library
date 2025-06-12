resources/views/laporan/anggota<!DOCTYPE html>
<html>
<head>
    <title>Laporan Buku</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { margin-top: 20px; font-size: 12px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Data Buku</h2>
        <p>Periode: {{ $tanggalLaporan }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>ISBN</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buku as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->isbn }}</td>
                    <td>{{ $item->kategori->nama }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>
                        {{ $item->jumlah > 0 ? 'Tersedia' : 'Habis' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Tersedia: {{ $totalTersedia }} | Total Habis: {{ $totalHabis }} </p>
    </div>
</body>
</html>