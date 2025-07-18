@extends('layouts.app')

@section('title', 'Laporan Buku')
@section('content')
<div class="container-fluid py-4">
    <!-- Filter Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-book me-2"></i> Filter Laporan Buku</h5>
                @if(request()->hasAny(['kategori_id', 'status', 'search']))
                <a href="{{ route('laporan.buku.index') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.buku.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="kategori_id" class="form-label">Kategori</label>
                        <select name="kategori_id" id="kategori_id" class="form-select form-select-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status Buku</label>
                        <select name="status" id="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="habis" {{ request('status') == 'habis' ? 'selected' : '' }}>Habis</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="search" class="form-label">Cari (Judul/Penulis/ISBN)</label>
                        <input type="text" name="search" id="search" class="form-control form-control-sm" 
                               value="{{ request('search') }}" placeholder="Kata kunci...">
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <button type="submit" class="btn btn-sm btn-dark">
                        <i class="bi bi-funnel me-1"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('laporan.buku.export.pdf', request()->query()) }}" class="btn btn-sm text-white btn-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                    </a>
                    <a href="{{ route('laporan.buku.export.excel', request()->query()) }}" class="btn btn-sm text-white btn-success">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Collection -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-collection text-primary fs-4"></i>
                    </div>
                    <h3 class="mb-1">{{ $totalKoleksi }}</h3>
                    <p class="text-muted mb-0">Total Koleksi Buku</p>
                </div>
            </div>
        </div>
        
        <!-- Available Books -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>
                    <h3 class="mb-1">{{ $totalTersedia }}</h3>
                    <p class="text-muted mb-0">Buku Tersedia</p>
                </div>
            </div>
        </div>
        
        <!-- Borrowed Books -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-arrow-up-circle text-warning fs-4"></i>
                    </div>
                    <h3 class="mb-1">{{ $totalDipinjam }}</h3>
                    <p class="text-muted mb-0">Sedang Dipinjam</p>
                </div>
            </div>
        </div>
        
        <!-- New Books -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-star text-info fs-4"></i>
                    </div>
                    <h3 class="mb-1">{{ $bukuBaru }}</h3>
                    <p class="text-muted mb-0">Buku Baru (30 hari)</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Data Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white border-bottom py-3">
            <h5 class="mb-0"><i class="bi bi-book-half me-2"></i> Data Buku</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50" class="bg-primary text-center text-white">#</th>
                            <th class="bg-primary text-white">Judul Buku</th>
                            <th class="bg-primary text-white">Kategori</th>
                            <th class="bg-primary text-center text-white">ISBN</th>
                            <th class="bg-primary text-center text-white">Penerbit</th>
                            <th class="bg-primary text-center text-white">Stok</th>
                            <th class="bg-primary text-center text-white">Status</th>
                            <th class="bg-primary text-center text-white">Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($buku as $key => $item)
                        <tr class="align-middle">
                            <td class="text-center">{{ $buku->firstItem() + $key }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->gambar_sampul)
                                        <img src="{{ asset('storage/'.$item->gambar_sampul) }}" 
                                             alt="{{ $item->judul }}" 
                                             class="img-thumbnail me-3" 
                                             style="width: 40px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 60px;">
                                            <i class="fas fa-book text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $item->judul }}</h6>
                                        <small class="text-muted">{{ $item->penulis }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->kategori->nama ?? '-' }}</td>
                            <td class="text-center">{{ $item->isbn ?? '-' }}</td>
                            <td class="text-center">{{ $item->penerbit->nama ?? '-' }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-center">
                                @php
                                    $badgeClass = [
                                        'tersedia' => 'bg-success',
                                        'habis' => 'bg-danger'
                                    ][$item->jumlah > 0 ? 'tersedia' : 'habis'];
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ $item->jumlah > 0 ? 'Tersedia' : 'Habis' }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{ $item->total_peminjaman ?? 0 }}x
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">Tidak ada data buku</h4>
                                    <p class="text-muted">Coba gunakan filter yang berbeda</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="5" class="text-end">Total</th>
                            <th class="text-center">{{ $buku->sum('jumlah') }} Stok</th>
                            <th class="text-center">
                                <span class="badge bg-success">{{ $totalTersedia }} Tersedia</span>
                                <span class="badge bg-danger ms-1">{{ $totalHabis }} Habis</span>
                            </th>
                            <th class="text-center">{{ $buku->sum('total_peminjaman') }}x Dipinjam</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        @if($buku->hasPages())
        <div class="card-footer bg-light">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted mb-2 mb-md-0">
                    Menampilkan <strong>{{ $buku->firstItem() }}</strong> sampai <strong>{{ $buku->lastItem() }}</strong> dari <strong>{{ $buku->total() }}</strong> entri
                </div>
                <div>
                    {{ $buku->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Additional Statistics -->
    <div class="row">
        <!-- Category Statistics -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-tags me-2"></i> Jumlah Buku By Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-end">Jumlah Buku</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($totalByKategori as $kategori)
                                <tr>
                                    <td>{{ $kategori['nama'] }}</td>
                                    <td class="text-end">{{ $kategori['total_buku'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Borrowings -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i> Peminjaman Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($peminjamanTerakhir as $pinjam)
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $pinjam->buku->judul ?? 'Buku Dihapus' }}</h6>
                                    <small class="text-muted">Oleh: {{ $pinjam->user->nama_lengkap }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge 
                                        @if($pinjam->status == 'dipinjam') bg-warning 
                                        @elseif($pinjam->status == 'dikembalikan') bg-success 
                                        @else bg-secondary @endif">
                                        {{ ucfirst($pinjam->status) }}
                                    </span>
                                    <div class="text-muted small mt-1">
                                        {{ $pinjam->tanggal_pinjam->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-journal-x fs-1"></i>
                            <p class="mt-2 mb-0">Belum ada data peminjaman</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Popular Books -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h6 class="mb-0"><i class="bi bi-star-fill me-2"></i> Buku Terpopuler</h6>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($bukuPopuler as $buku)
                <div class="col-md-4 col-lg-2 mb-3">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($buku->gambar_sampul)
                        <img src="{{ asset('storage/'.$buku->gambar_sampul) }}" class="card-img-top" alt="{{ $buku->judul }}" style="height: 150px; object-fit: cover;">
                        @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 150px;">
                            <i class="fas fa-book fa-3x text-white"></i>
                        </div>
                        @endif
                        <div class="card-body text-center">
                            <h6 class="card-title mb-1">{{ Str::limit($buku->judul, 20) }}</h6>
                            <small class="text-muted">{{ $buku->penulis }}</small>
                            <div class="mt-2">
                                <span class="badge bg-primary">
                                    {{ $buku->peminjaman_count }}x dipinjam
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-4 text-muted">
                    <i class="bi bi-journal-x fs-1"></i>
                    <p class="mt-2 mb-0">Belum ada data buku populer</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection