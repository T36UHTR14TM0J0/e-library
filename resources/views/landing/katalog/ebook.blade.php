@extends('landing.app')
@section('title', 'Koleksi Ebook')
@section('content')
<section class="py-5">
    <div class="container">
        <!-- Header Section -->
        <div class="text-center mb-5 mt-5">
            <h1 class="display-5 fw-bold text-gradient">Koleksi Ebook</h1>
            <p class="lead text-muted">Akses ribuan buku digital kapan saja, di mana saja</p>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('ebook') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" 
                                   class="form-control border-start-0" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Cari ebook...">
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
                        <a href="{{ route('ebook') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Section - Vertical Card List -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-0">
                @forelse($ebooks as $ebook)
                <div class="ebook-list-item border-bottom p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $ebook->judul }}</h5>
                            <p class="text-muted mb-2">{{ $ebook->penulis }}</p>
                            
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ $ebook->kategori->nama ?? 'Umum' }}
                                </span>
                                @if($ebook->prodi)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    {{ $ebook->prodi->nama }}
                                </span>
                                @endif
                                <span class="badge {{ $ebook->izin_unduh ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }}">
                                    <i class="fas {{ $ebook->izin_unduh ? 'fa-download' : 'fa-lock' }} me-1"></i>
                                    {{ $ebook->izin_unduh ? 'Bisa Diunduh' : 'Baca Online' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center ms-3">
                            <span class="badge bg-success rounded-pill me-2">
                                <i class="fas fa-eye me-1"></i> {{ $ebook->total_dibaca }}
                            </span>
                            <a href="{{ route('detail_ebook', ['id' => $ebook->id]) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-book-open me-2"></i> Tidak ada ebook yang ditemukan
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($ebooks->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $ebooks->withQueryString()->links('vendor.pagination.bootstrap-5') }}
        </div>
        @endif
    </div>
</section>

<style>
    .text-gradient {
        background: linear-gradient(90deg, #10b981 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .ebook-list-item {
        transition: background-color 0.2s ease;
    }
    
    .ebook-list-item:hover {
        background-color: rgba(16, 185, 129, 0.05);
    }
    
    .ebook-list-item:last-child {
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
        background-color: #10b981;
        border-color: #10b981;
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