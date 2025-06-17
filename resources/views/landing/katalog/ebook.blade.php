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
                    <div class="col-md-6">
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
                    
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Section -->
        <div class="row g-4">
            @forelse($ebooks as $ebook)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                    <div class="card-img-container position-relative">
                        @if($ebook->gambar_sampul)
                            <img src="{{ asset('storage/' . $ebook->gambar_sampul) }}" 
                                 class="card-img-top" 
                                 alt="{{ $ebook->judul }}">
                        @else
                            <div class="no-cover d-flex align-items-center justify-content-center">
                                <i class="fas fa-file-pdf text-danger"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-success rounded-pill">
                                <i class="fas fa-eye me-1"></i> {{ $ebook->total_dibaca }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title text-truncate">{{ $ebook->judul }}</h5>
                        <p class="card-text text-muted mb-2">{{ $ebook->penulis }}</p>
                        
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $ebook->kategori->nama ?? 'Umum' }}
                            </span>
                            @if($ebook->prodi)
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                {{ $ebook->prodi->nama }}
                            </span>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge {{ $ebook->izin_unduh ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }}">
                                <i class="fas {{ $ebook->izin_unduh ? 'fa-download' : 'fa-lock' }} me-1"></i>
                                {{ $ebook->izin_unduh ? 'Bisa Diunduh' : 'Baca Online' }}
                            </span>
                            
                            <a href="{{ route('detail_ebook', ['id' => $ebook->id]) }}" 
                            class="btn btn-sm btn-outline-primary rounded-circle">
                            <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white border-0 pt-0">
                        <small class="text-muted d-block text-center">
                            <i class="fas fa-info-circle me-1"></i> Login untuk membaca
                        </small>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-book-open me-2"></i> Tidak ada ebook yang ditemukan
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($ebooks->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $ebooks->withQueryString()->links() }}
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
    
    .card-img-container {
        height: 200px;
        overflow: hidden;
    }
    
    .card-img-top {
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .no-cover {
        height: 100%;
        background-color: #f8f9fa;
        color: #dc3545;
        font-size: 3rem;
    }
    
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .hover-shadow:hover .card-img-top {
        transform: scale(1.05);
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
    
    .text-truncate {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
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