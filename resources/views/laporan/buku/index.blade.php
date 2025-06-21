@extends('layouts.app')

@section('title', 'Laporan Buku')
@section('content')
<div class="container-fluid py-4">
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
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="search" class="form-label">Cari (Judul/ISBN)</label>
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
    
    <div class="card shadow-sm">
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
                            <td class="text-center">{{ $key + 1 }}</td>
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
                            <td>{{ $item->kategori->nama }}</td>
                            <td class="text-center">{{ $item->isbn ?? '-' }}</td>
                            <td class="text-center">{{ $item->penerbit->nama }}</td>
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
                                {{ $item->peminjaman_count ?? 0 }}x
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
                            <th class="text-center">{{ $buku->sum('peminjaman_count') }}x Dipinjam</th>
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
</div>
@endsection