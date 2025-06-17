@extends('landing.app')
@section('title', 'Detail Buku')
@section('content')
<section class="py-5">
    <div class="container">
        <!-- Header Section -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-gradient">Detail Buku</h1>
            <p class="lead text-muted">Informasi lengkap tentang buku fisik</p>
        </div>

        <div class="card border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="row">
                    <!-- Cover Image Column -->
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="card-img-container rounded-3 overflow-hidden" style="height: 400px;">
                            @if($buku->gambar_sampul)
                                <img src="{{ asset('storage/' . $buku->gambar_sampul) }}" 
                                     class="img-fluid w-100 h-100 object-fit-cover" 
                                     alt="Cover {{ $buku->judul }}">
                            @else
                                <div class="no-cover d-flex align-items-center justify-content-center bg-light h-100">
                                    <i class="fas fa-book-open text-muted" style="font-size: 5rem;"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Details Column -->
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-3">{{ $buku->judul }}</h2>
                        <p class="text-muted mb-4">{{ $buku->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>

                        <div class="row g-3 mb-4">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-barcode text-primary me-2"></i>
                                    <span class="fw-semibold">ISBN:</span>
                                    <span class="ms-2">{{ $buku->isbn ?? '-' }}</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user-edit text-primary me-2"></i>
                                    <span class="fw-semibold">Penulis:</span>
                                    <span class="ms-2">{{ $buku->penulis ?? '-' }}</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-building text-primary me-2"></i>
                                    <span class="fw-semibold">Penerbit:</span>
                                    <span class="ms-2">{{ $buku->penerbit->nama ?? '-' }}</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    <span class="fw-semibold">Tahun Terbit:</span>
                                    <span class="ms-2">{{ $buku->tahun_terbit ?? '-' }}</span>
                                </div>
                            </div>
                            
                            <!-- Categories -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-tag text-primary me-2"></i>
                                    <span class="fw-semibold">Kategori:</span>
                                    <span class="ms-2">
                                        @if($buku->kategori)
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                {{ $buku->kategori->nama }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-graduation-cap text-primary me-2"></i>
                                    <span class="fw-semibold">Program Studi:</span>
                                    <span class="ms-2">
                                        @if($buku->prodi)
                                            {{ $buku->prodi->nama }} ({{ $buku->prodi->kode }})
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Stock Information -->
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-boxes text-primary me-2"></i>
                                    <span class="fw-semibold">Total Stok:</span>
                                    <span class="ms-2 badge bg-success">
                                        {{ $buku->jumlah_stok() }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-book-reader text-primary me-2"></i>
                                    <span class="fw-semibold">Dipinjam:</span>
                                    <span class="ms-2 badge bg-primary">
                                        {{ $buku->dipinjam() }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    <span class="fw-semibold">Tersedia:</span>
                                    <span class="ms-2 badge bg-{{ $buku->jumlahTersedia() > 0 ? 'success' : 'danger' }}">
                                        {{ $buku->jumlahTersedia() }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Dates -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-plus text-primary me-2"></i>
                                    <span class="fw-semibold">Ditambahkan:</span>
                                    <span class="ms-2">{{ $buku->created_at->locale('id')->translatedFormat('d F Y') }}</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-check text-primary me-2"></i>
                                    <span class="fw-semibold">Terakhir Update:</span>
                                    <span class="ms-2">{{ $buku->updated_at->locale('id')->translatedFormat('d F Y') }}</span>
                                </div>
                            </div>
                            
                            <!-- Status -->
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    <span class="fw-semibold">Status:</span>
                                    <span class="ms-2 badge bg-{{ $buku->tersedia() ? 'success' : 'danger' }}">
                                        {{ $buku->tersedia() ? 'Tersedia' : 'Tidak Tersedia' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <a href="{{ route('buku-fisik') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali ke Koleksi Buku
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        border: 1px solid #e9ecef;
    }
    
    .no-cover {
        color: #6c757d;
    }
    
    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
    
    .fw-semibold {
        font-weight: 600;
    }
    
    .object-fit-cover {
        object-fit: cover;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enable tooltips if needed
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection