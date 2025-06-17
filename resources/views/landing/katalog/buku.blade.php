@extends('landing.app')
@section('title', 'Buku Fisik')
@section('content')
<section class="py-5 mt-5">
    <div class="container">
        <!-- Header Section -->
        <div class="text-center mb-6 mt-5">
            <h1 class="display-5 fw-bold text-gradient">Koleksi Buku Fisik</h1>
            <p class="lead text-muted">Temukan buku-buku berkualitas dari koleksi perpustakaan kami</p>
        </div>

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-4">
                <form action="{{ route('buku-fisik') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <!-- Combined Search Field -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase text-muted">Cari Buku</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" 
                                    class="form-control border-start-0" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Judul, penulis, kategori, prodi, atau tahun...">
                            </div>
                        </div>
                        
                        <!-- Sorting Dropdown -->
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-uppercase text-muted">Urutkan</label>
                            <select class="form-select" name="sort">
                                <option value="judul" {{ request('sort') == 'judul' ? 'selected' : '' }}>A-Z</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Paling Banyak Dipinjam</option>
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Terbaru</option>
                            </select>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-search me-2"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <!-- Table Header -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 ps-4" width="60px">No</th>
                                <th class="border-0">Judul Buku</th>
                                <th class="border-0">Penulis</th>
                                <th class="border-0">Kategori</th>
                                <th class="border-0">Prodi</th>
                                <th class="border-0 text-center">Tahun</th>
                                <th class="border-0 text-center">Peminjaman</th>
                                <th class="border-0 text-center">Status</th>
                                {{-- <th class="border-0 pe-4 text-end">Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bukus as $index => $buku)
                            <tr class="border-top">
                                <td class="ps-4 text-muted">{{ $index + $bukus->firstItem() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($buku->gambar_sampul)
                                            <img src="{{ asset('storage/' . $buku->gambar_sampul) }}" 
                                                 class="rounded me-3 shadow-sm" width="45" height="65" 
                                                 style="object-fit: cover;" alt="{{ $buku->judul }}">
                                        @else
                                            <div class="rounded bg-light me-3 d-flex align-items-center justify-content-center shadow-sm" 
                                                 style="width: 45px; height: 65px;">
                                                <i class="fas fa-book text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $buku->judul }}</h6>
                                            @if($buku->penerbit)
                                                <small class="text-muted">{{ $buku->penerbit->nama }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $buku->penulis }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-opacity-10 bg-dark text-dark">
                                        {{ $buku->kategori->nama ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $buku->prodi->nama ?? '-' }}</td>
                                <td class="text-center text-muted">{{ $buku->tahun_terbit }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-opacity-10 {{ $buku->total_peminjaman > 0 ? 'bg-success text-success' : 'bg-secondary text-secondary' }}">
                                        {{ $buku->total_peminjaman }}x
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($buku->jumlah > 0)
                                        <span class="badge rounded-pill bg-opacity-10 bg-primary text-primary">
                                            <i class="fas fa-check-circle me-1"></i> Tersedia
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-opacity-10 bg-danger text-danger">
                                            <i class="fas fa-times-circle me-1"></i> Habis
                                        </span>
                                    @endif
                                </td>
                                {{-- <td class="pe-4 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="#" class="btn btn-sm btn-outline-dark rounded-circle" title="Detail" data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($buku->jumlah > 0)
                                            <a href="#" class="btn btn-sm btn-success rounded-circle" title="Pinjam" data-bs-toggle="tooltip">
                                                <i class="fas fa-book-open"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination and Results Info -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 border-top">
                    <div class="text-muted mb-3 mb-md-0">
                        Menampilkan <span class="fw-bold">{{ $bukus->firstItem() }}</span> sampai <span class="fw-bold">{{ $bukus->lastItem() }}</span> 
                        dari <span class="fw-bold">{{ $bukus->total() }}</span> buku
                    </div>
                    <div>
                        {{ $bukus->withQueryString()->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- @push('styles') --}}
<style>
    .text-gradient {
        background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }
    .badge.bg-opacity-10 {
        padding: 0.35em 0.65em;
    }
    .page-link {
        border-radius: 50% !important;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 3px;
        border: none;
    }
    .page-item.active .page-link {
        background-color: #3b82f6;
    }
</style>
{{-- @endpush --}}

@push('scripts')
<script>
    // Enable tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>
@endpush
@endsection