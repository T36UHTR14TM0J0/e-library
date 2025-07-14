<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Anggota</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin-bottom: 5px;
        }
        .header p {
            margin-top: 0;
            color: #666;
        }
        .info {
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            color: #666;
        }
        .summary {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Data Anggota</h2>
        <p>Dicetak pada: {{ $tanggalLaporan }}</p>
    </div>
    
    <div class="info">
        @if($role)
        <p><strong>Filter Role:</strong> {{ ucfirst($role) }}</p>
        @endif
        @if($search)
        <p><strong>Filter Pencarian:</strong> {{ $search }}</p>
        @endif
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Role</th>
                <th>NPM/NIDN</th>
                <th>Program Studi</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $key => $user)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $user->nama_lengkap }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>{{ $user->role == 'mahasiswa' ? $user->npm : $user->nidn }}</td>
                <td>{{ $user->prodi ? $user->prodi->nama : '-' }}</td>
                <td>{{ $user->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="summary">
        <p>Total Dosen: {{ $totalDosen }}</p>
        <p>Total Mahasiswa: {{ $totalMahasiswa }}</p>
        <p>Total Semua Anggota: {{ $totalDosen + $totalMahasiswa }}</p>
    </div>
    
    <div class="footer">
        <p>Dicetak oleh Sistem Perpustakaan</p>
    </div>
</body>
</html>