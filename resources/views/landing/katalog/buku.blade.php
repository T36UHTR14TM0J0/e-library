@extends('landing.app')
@section('title', 'Buku Fisik')
@section('content')
<section class="py-5">
    <div class="container">
        <!-- Header Section -->
        <div class="text-center mb-5 mt-5">
            <h1 class="display-5 fw-bold text-gradient">Koleksi Buku Fisik</h1>
            <p class="lead text-muted">Temukan buku-buku berkualitas dari koleksi perpustakaan kami</p>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('buku-fisik') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" 
                                   class="form-control border-start-0" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Cari buku...">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <select class="form-select" name="sort">
                            <option value="judul" {{ request('sort') == 'judul' ? 'selected' : '' }}>A-Z</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Populer</option>
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Terbaru</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                        <a href="{{ route('buku-fisik') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Section - Vertical Card List -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @forelse($bukus as $buku)
                <div class="book-list-item border-bottom p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $buku->judul }}</h5>
                            <p class="text-muted mb-2">{{ $buku->penulis }}</p>
                            
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ $buku->kategori->nama ?? 'Umum' }}
                                </span>
                                @if($buku->prodi)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    {{ $buku->prodi->nama }}
                                </span>
                                @endif
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $buku->tahun_terbit }}
                                </span>
                                <span class="badge {{ $buku->jumlah > 0 ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}">
                                    <i class="fas {{ $buku->jumlah > 0 ? 'fa-check' : 'fa-times' }} me-1"></i>
                                    {{ $buku->jumlah > 0 ? 'Tersedia' : 'Habis' }} ({{ $buku->jumlah }})
                                </span>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center ms-3">
                            <span class="badge bg-success rounded-pill me-2">
                                <i class="fas fa-book-open me-1"></i> {{ $buku->total_peminjaman }}
                            </span>
                            <a href="{{ route('detail_buku', ['id' => $buku->id]) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-book-open me-2"></i> Tidak ada buku yang ditemukan
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($bukus->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $bukus->withQueryString()->links('vendor.pagination.bootstrap-5') }}
        </div>
        @endif
    </div>
</section>

<style>
    .text-gradient {
        background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .book-list-item {
        transition: background-color 0.2s ease;
    }
    
    .book-list-item:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }
    
    .book-list-item:last-child {
        border-bottom: none !important;
    }
    
    .page-link {
        border-radius: 50% !important;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 3px;
    }
    
    .page-item.active .page-link {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enable tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Smooth scroll to top when paginating
        document.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    });
</script>
@endpush
@endsection