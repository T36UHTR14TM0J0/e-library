@extends('layouts.app')

@section('title', 'Laporan Ebook')
@section('content')
<div class="container-fluid py-4">
    <!-- Filter Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-book me-2"></i> Filter Laporan Ebook</h5>
                @if(request()->hasAny(['kategori_id', 'prodi_id', 'status', 'search']))
                <a href="{{ route('laporan.ebook.index') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.ebook.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
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
                    
                    <div class="col-md-3">
                        <label for="prodi_id" class="form-label">Program Studi</label>
                        <select name="prodi_id" id="prodi_id" class="form-select form-select-sm">
                            <option value="">Semua Prodi</option>
                            @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status Unduh</label>
                        <select name="status" id="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="dapat_diunduh" {{ request('status') == 'dapat_diunduh' ? 'selected' : '' }}>Dapat Diunduh</option>
                            <option value="tidak_dapat_diunduh" {{ request('status') == 'tidak_dapat_diunduh' ? 'selected' : '' }}>Tidak Dapat Diunduh</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="search" class="form-label">Cari (Judul/Penulis)</label>
                        <input type="text" name="search" id="search" class="form-control form-control-sm" 
                               value="{{ request('search') }}" placeholder="Kata kunci...">
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <button type="submit" class="btn btn-sm btn-dark">
                        <i class="bi bi-funnel me-1"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('laporan.ebook.export.pdf', request()->query()) }}" class="btn btn-sm text-white btn-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                    </a>
                    <a href="{{ route('laporan.ebook.export.excel', request()->query()) }}" class="btn btn-sm text-white btn-success">
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
                    <p class="text-muted mb-0">Total Koleksi Ebook</p>
                </div>
            </div>
        </div>
        
        <!-- Downloadable Ebooks -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-download text-success fs-4"></i>
                    </div>
                    <h3 class="mb-1">{{ $totalDapatDiunduh }}</h3>
                    <p class="text-muted mb-0">Dapat Diunduh</p>
                </div>
            </div>
        </div>
        
        <!-- Total Reads -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-eye text-info fs-4"></i>
                    </div>
                    <h3 class="mb-1">{{ $totalDibaca }}</h3>
                    <p class="text-muted mb-0">Total Dibaca</p>
                </div>
            </div>
        </div>
        
        <!-- New Ebooks -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-star text-warning fs-4"></i>
                    </div>
                    <h3 class="mb-1">{{ $ebookBaru }}</h3>
                    <p class="text-muted mb-0">Ebook Baru (30 hari)</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Data Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white border-bottom py-3">
            <h5 class="mb-0"><i class="bi bi-book-half me-2"></i> Data Ebook</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50" class="bg-primary text-center text-white">#</th>
                            <th class="bg-primary text-white">Judul Ebook</th>
                            <th class="bg-primary text-white">Kategori</th>
                            <th class="bg-primary text-white">Prodi</th>
                            <th class="bg-primary text-center text-white">Penerbit</th>
                            <th class="bg-primary text-center text-white">Status Unduh</th>
                            <th class="bg-primary text-center text-white">Dibaca</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ebooks as $key => $item)
                        <tr class="align-middle">
                            <td class="text-center">{{ $ebooks->firstItem() + $key }}</td>
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
                            <td>{{ $item->prodi->nama ?? '-' }}</td>
                            <td class="text-center">{{ $item->penerbit->nama ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge {{ $item->izin_unduh ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $item->izin_unduh ? 'Dapat Diunduh' : 'Tidak Dapat Diunduh' }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{ $item->total_dibaca ?? 0 }}x
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">Tidak ada data ebook</h4>
                                    <p class="text-muted">Coba gunakan filter yang berbeda</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="5" class="text-end">Total</th>
                            <th class="text-center">
                                <span class="badge bg-success">{{ $totalDapatDiunduh }} Dapat Diunduh</span>
                                <span class="badge bg-secondary ms-1">{{ $totalTidakDapatDiunduh }} Tidak Dapat Diunduh</span>
                            </th>
                            <th class="text-center">{{ $ebooks->sum('total_dibaca') }}x Dibaca</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        @if($ebooks->hasPages())
        <div class="card-footer bg-light">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted mb-2 mb-md-0">
                    Menampilkan <strong>{{ $ebooks->firstItem() }}</strong> sampai <strong>{{ $ebooks->lastItem() }}</strong> dari <strong>{{ $ebooks->total() }}</strong> entri
                </div>
                <div>
                    {{ $ebooks->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
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
                    <h6 class="mb-0"><i class="bi bi-tags me-2"></i> Jumlah Ebook By Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-end">Jumlah Ebook</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($totalByKategori as $kategori)
                                <tr>
                                    <td>{{ $kategori['nama'] }}</td>
                                    <td class="text-end">{{ $kategori['total_ebook'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Readings -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i> Pembacaan Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($pembacaanTerakhir as $baca)
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $baca->ebook->judul ?? 'Ebook Dihapus' }}</h6>
                                    <small class="text-muted">Oleh: {{ $baca->user->nama_lengkap }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted small mt-1">
                                        {{ $baca->created_at->format('d M Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-journal-x fs-1"></i>
                            <p class="mt-2 mb-0">Belum ada data pembacaan</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Popular Ebooks -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h6 class="mb-0"><i class="bi bi-star-fill me-2"></i> Ebook Terpopuler</h6>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($ebookPopuler as $ebook)
                <div class="col-md-4 col-lg-2 mb-3">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($ebook->gambar_sampul)
                        <img src="{{ asset('storage/'.$ebook->gambar_sampul) }}" class="card-img-top" alt="{{ $ebook->judul }}" style="height: 150px; object-fit: cover;">
                        @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 150px;">
                            <i class="fas fa-book fa-3x text-white"></i>
                        </div>
                        @endif
                        <div class="card-body text-center">
                            <h6 class="card-title mb-1">{{ Str::limit($ebook->judul, 20) }}</h6>
                            <small class="text-muted">{{ $ebook->penulis }}</small>
                            <div class="mt-2">
                                <span class="badge bg-primary">
                                    {{ $ebook->readings_count }}x dibaca
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-4 text-muted">
                    <i class="bi bi-journal-x fs-1"></i>
                    <p class="mt-2 mb-0">Belum ada data ebook populer</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection