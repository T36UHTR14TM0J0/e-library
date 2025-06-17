@extends('landing.app')
@section('title', 'Detail Ebook')
@section('content')
<section class="py-5">
    <div class="container">
        <!-- Header Section -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-gradient">Detail Ebook</h1>
            <p class="lead text-muted">Informasi lengkap tentang ebook</p>
        </div>

        <div class="card border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="row">
                    <!-- Cover Image Column -->
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="card-img-container rounded-3 overflow-hidden">
                            @if($ebook->gambar_sampul)
                                <img src="{{ asset('storage/' . $ebook->gambar_sampul) }}" 
                                     class="img-fluid w-100 h-auto" 
                                     alt="Cover {{ $ebook->judul }}">
                            @else
                                <div class="no-cover d-flex align-items-center justify-content-center bg-light" style="height: 100%;">
                                    <i class="fas fa-book-open text-muted" style="font-size: 5rem;"></i>
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- Details Column -->
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-3">{{ $ebook->judul }}</h2>
                        <p class="text-muted mb-4">{{ $ebook->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user-edit text-primary me-2"></i>
                                    <span class="fw-semibold">Penulis:</span>
                                    <span class="ms-2">{{ $ebook->penulis ?? '-' }}</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-building text-primary me-2"></i>
                                    <span class="fw-semibold">Penerbit:</span>
                                    <span class="ms-2">{{ $ebook->penerbit->nama ?? '-' }}</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-tag text-primary me-2"></i>
                                    <span class="fw-semibold">Kategori:</span>
                                    <span class="ms-2">
                                        @if($ebook->kategori)
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                {{ $ebook->kategori->nama }}
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
                                        @if($ebook->prodi)
                                            {{ $ebook->prodi->nama }} ({{ $ebook->prodi->kode }})
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file text-primary me-2"></i>
                                    <span class="fw-semibold">Format:</span>
                                    <span class="ms-2">
                                        @if($ebook->file_url)
                                            {{ strtoupper(pathinfo($ebook->file_url, PATHINFO_EXTENSION)) }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-weight text-primary me-2"></i>
                                    <span class="fw-semibold">Ukuran:</span>
                                    <span class="ms-2">
                                        @if($ebook->file_url && Storage::disk('public')->exists($ebook->file_url))
                                            {{ round(Storage::disk('public')->size($ebook->file_url) / 1024 / 1024, 2) }} MB
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-download text-primary me-2"></i>
                                    <span class="fw-semibold">Unduhan:</span>
                                    <span class="ms-2">
                                        <span class="badge {{ $ebook->izin_unduh ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }}">
                                            {{ $ebook->izin_unduh ? 'Diizinkan' : 'Tidak Diizinkan' }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-eye text-primary me-2"></i>
                                    <span class="fw-semibold">Dibaca:</span>
                                    <span class="ms-2">{{ $ebook->total_reads }} kali</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    <span class="fw-semibold">Diunggah Oleh:</span>
                                    <span class="ms-2">{{ $ebook->pengunggah->nama_lengkap ?? '-' }}</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-plus text-primary me-2"></i>
                                    <span class="fw-semibold">Tanggal Unggah:</span>
                                    <span class="ms-2">{{ $ebook->created_at->locale('id')->translatedFormat('d F Y') }}</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-check text-primary me-2"></i>
                                    <span class="fw-semibold">Terakhir Update:</span>
                                    <span class="ms-2">{{ $ebook->updated_at->locale('id')->translatedFormat('d F Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <a href="{{ route('ebook') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali ke Koleksi Ebook
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
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
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