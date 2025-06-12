<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Detail Peminjaman - {{ $peminjaman->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1, h2, h3, h4, h5 { margin: 0; }
        .card { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; }
        .card-header { background-color: #f8f9fa; padding: 10px; margin: -15px -15px 15px -15px; }
        .row { display: flex; flex-wrap: wrap; margin-right: -15px; margin-left: -15px; }
        .col-6 { flex: 0 0 50%; max-width: 50%; padding: 0 15px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        .badge { padding: 5px 10px; border-radius: 3px; font-size: 12px; }
        .bg-secondary { background-color: #6c757d; color: white; }
        .bg-info { background-color: #17a2b8; color: white; }
        .bg-success { background-color: #28a745; color: white; }
        .bg-warning { background-color: #ffc107; color: black; }
        .bg-danger { background-color: #dc3545; color: white; }
        dt { font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-4">
            <h3>DETAIL PEMINJAMAN BUKU</h3>
            <h5>E-LIBRARY UNIVERSITAS</h5>
        </div>
        
        <!-- Status -->
        <div class="mb-4">
            @php
                $statusColors = [
                    'menunggu'      => 'bg-secondary',
                    'dipinjam'      => 'bg-info',
                    'dikembalikan'  => 'bg-success',
                    'terlambat'     => 'bg-warning',
                    'dibatalkan'    => 'bg-danger'
                ];
            @endphp
            <span class="badge {{ $statusColors[$peminjaman->status] ?? 'bg-secondary' }}">
                Status: {{ ucfirst($peminjaman->status) }}
            </span>
        </div>
        
        <div class="row">
            <!-- Book Information -->
            <div class="col-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Informasi Buku</h5>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <td width="30%">Judul Buku</td>
                                <td>{{ $peminjaman->buku->judul ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Kategori</td>
                                <td>{{ $peminjaman->buku->kategori->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Program Studi</td>
                                <td>{{ $peminjaman->buku->prodi->nama ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Borrower Information -->
            <div class="col-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Informasi Peminjam</h5>
                    </div>
                    <div class="card-body">
                        <table>
                            @if($peminjaman->user->role != 'admin')
                            <tr>
                                <td width="30%">NPM/NIDN</td>
                                <td>{{ $peminjaman->user->role == 'mahasiswa' ? $peminjaman->user->npm : ($peminjaman->user->role == 'dosen' ? $peminjaman->user->nidn : '-') }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td>Nama Lengkap</td>
                                <td>{{ $peminjaman->user->nama_lengkap }}</td>
                            </tr>
                            @if($peminjaman->user->role != 'admin')
                            <tr>
                                <td>Program Studi</td>
                                <td>{{ $peminjaman->user->prodi->nama ?? '-' }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Loan Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Detail Peminjaman</h5>
            </div>
            <div class="card-body">
                <table>
                    <tr>
                        <td width="30%">Tanggal Pinjam</td>
                        <td>{{ $peminjaman->tanggal_pinjam->locale('id')->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Jatuh Tempo</td>
                        <td>{{ $peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Lama Pinjam</td>
                        <td>{{ $peminjaman->tanggal_pinjam->diffInDays(now()) }} hari</td>
                    </tr>
                    @if($peminjaman->tanggal_kembali)
                    <tr>
                        <td>Tanggal Kembali</td>
                        <td>{{ $peminjaman->tanggal_kembali->locale('id')->translatedFormat('d F Y') }}</td>
                    </tr>
                    @endif
                    @if($peminjaman->isLate())
                    <tr>
                        <td>Denda</td>
                        <td>Rp {{ number_format($peminjaman->hitungDenda(), 2) }}</td>
                    </tr>
                    @endif
                    @if($peminjaman->disetujui_oleh)
                    <tr>
                        <td>Disetujui Oleh</td>
                        <td>{{ $peminjaman->disetujui->nama_lengkap ?? '-' }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        
        <!-- Notes Section -->
        @if($peminjaman->tanggal_pinjam || $peminjaman->tanggal_batal || $peminjaman->tanggal_setujui || $peminjaman->tanggal_kembali)
        <div class="card">
            <div class="card-header">
                <h5>Histori</h5>
            </div>
            <div class="card-body">
                <table>
                    @if($peminjaman->tanggal_pinjam)
                    <tr>
                        <td width="30%">Tanggal Pinjam</td>
                        <td>{{ $peminjaman->tanggal_pinjam->locale('id')->translatedFormat('d F Y') }}</td>
                        <td>Catatan</td>
                        <td>{{ $peminjaman->catatan_pinjam }}</td>
                    </tr>
                    @endif
                    
                    @if($peminjaman->tanggal_batal)
                    <tr>
                        <td>Tanggal Dibatalkan</td>
                        <td>{{ $peminjaman->tanggal_batal->locale('id')->translatedFormat('d F Y') }}</td>
                        <td>Catatan</td>
                        <td>{{ $peminjaman->catatan_batal }}</td>
                    </tr>
                    @endif
                    
                    @if($peminjaman->tanggal_setujui)
                    <tr>
                        <td>Tanggal Disetujui</td>
                        <td>{{ $peminjaman->tanggal_setujui->locale('id')->translatedFormat('d F Y') }}</td>
                        <td>Catatan</td>
                        <td>{{ $peminjaman->catatan_setujui }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif
    </div>
</body>
</html>