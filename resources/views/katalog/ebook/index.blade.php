@extends('layouts.app')
@section('title', 'Katalog E-Book')
@section('content')

<div class="container py-4">
    <!-- Header Section -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-gradient">Katalog E-Book</h1>
        <p class="lead text-muted">Temukan koleksi ebook terlengkap untuk kebutuhan akademik Anda</p>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('KatalogEbook.index') }}" class="row g-2 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-start-0" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari judul, penulis, kategori, prodi, atau penerbit...">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="">Default</option>
                        <option value="sering_dibaca" {{ request('sort') == 'sering_dibaca' ? 'selected' : '' }}>Populer</option>
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    </select>
                </div>
                
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'sort', 'izin_unduh']))
                    <a href="{{ route('KatalogEbook.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Results Section - Vertical Card List -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-body p-0">
            @forelse($ebooks as $ebook)
            <div class="ebook-list-item border-bottom p-3">
                <div class="d-flex">
                    <!-- Cover Image -->
                    {{-- <div class="flex-shrink-0 me-3" style="width: 120px;">
                        <img src="{{ asset($ebook->gambar_sampul ? 'storage/' . $ebook->gambar_sampul : 'assets/images/default-cover.png') }}" 
                             alt="Cover {{ $ebook->judul }}" 
                             class="img-fluid rounded border"
                             style="height: 160px; width: 120px; object-fit: cover;"
                             onerror="this.src='{{ asset('assets/images/default-cover.png') }}'">
                    </div> --}}
                    
                    <!-- Ebook Details -->
                    <div class="flex-grow-1">
                        <h5 class="mb-1">{{ $ebook->judul }}</h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-user-edit me-1"></i> {{ $ebook->penulis }}
                        </p>
                        
                        <!-- Badges -->
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $ebook->kategori->nama ?? 'Umum' }}
                            </span>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                {{ $ebook->prodi->nama ?? '-' }}
                            </span>
                            <span class="badge {{ $ebook->izin_unduh ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }}">
                                <i class="fas {{ $ebook->izin_unduh ? 'fa-download' : 'fa-lock' }} me-1"></i>
                                {{ $ebook->izin_unduh ? 'Diizinkan Unduh' : 'Baca Online' }}
                            </span>
                            <span class="badge bg-info bg-opacity-10 text-info">
                                <i class="fas fa-eye me-1"></i> {{ $ebook->total_reads }}x
                            </span>
                        </div>
                        
                        <!-- Upload Info -->
                        <p class="text-muted small mb-2">
                            <i class="fas fa-calendar-alt me-1"></i> 
                            {{ $ebook->created_at->locale('id')->translatedFormat('d F Y') }}
                            <span class="mx-2">•</span>
                            <i class="fas fa-user me-1"></i> 
                            {{ $ebook->pengunggah->nama_lengkap ?? '-' }}
                        </p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex-shrink-0 ms-3 d-flex flex-column justify-content-between">
                        <div class="text-end">
                            <span class="badge bg-light text-dark">
                                {{ strtoupper($ebook->file_type) ?? 'PDF' }}
                            </span>
                        </div>
                        
                        <div class="btn-group">
                            <a href="{{ route('KatalogEbook.show', $ebook->id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                            
                            @if($ebook->file_url)
                                @if($ebook->file_type === 'pdf')
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#readPdfModal{{ $ebook->id }}">
                                        <i class="fas fa-book-open me-1"></i> Baca
                                    </button>
                                @elseif($ebook->file_type === 'epub')
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#readEpubModal{{ $ebook->id }}">
                                        <i class="fas fa-book-open me-1"></i> Baca
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal PDF -->
            @if($ebook->file_type === 'pdf')
            <div class="modal fade" id="readPdfModal{{ $ebook->id }}" tabindex="-1" aria-labelledby="readPdfModalLabel{{ $ebook->id }}" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="readPdfModalLabel{{ $ebook->id }}">Membaca: {{ $ebook->judul }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0" style="height: 70vh;">
                            @if($ebook->file_exists)
                                @if(filter_var($ebook->file_url, FILTER_VALIDATE_URL))
                                    <!-- Untuk URL eksternal -->
                                    <iframe 
                                        src="https://docs.google.com/viewer?url={{ urlencode($ebook->file_url) }}&embedded=true" 
                                        frameborder="0" 
                                        style="width: 100%; height: 100%;"
                                    ></iframe>
                                @else
                                    <!-- Untuk file lokal -->
                                    <iframe 
                                        src="{{ Storage::url($ebook->file_url) }}#toolbar=0&navpanes=0&scrollbar=0" 
                                        frameborder="0" 
                                        style="width: 100%; height: 100%;"
                                    ></iframe>
                                @endif
                            @else
                                <div class="alert alert-danger m-3">
                                    File tidak ditemukan atau tidak dapat diakses.
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary text-white btn-sm" data-bs-dismiss="modal">Tutup</button>
                            @if($ebook->izin_unduh)
                                <a href="{{ Storage::url($ebook->file_url) }}" class="btn btn-primary btn-sm" download>
                                    <i class="fas fa-download me-1"></i> Unduh
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- EPUB Reader Modal -->
            @if($ebook->file_url && pathinfo($ebook->file_url, PATHINFO_EXTENSION) === 'epub')
            <div class="modal fade" id="readEpubModal{{ $ebook->id }}" tabindex="-1" aria-labelledby="readEpubModalLabel{{ $ebook->id }}" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="readEpubModalLabel{{ $ebook->id }}">Membaca: {{ $ebook->judul }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0" style="height: 70vh;">
                            <div id="epub-viewer-{{ $ebook->id }}" style="width: 100%; height: 100%;"></div>
                        </div>
                        <div class="modal-footer">
                            <div class="me-auto">
                                <button type="button" class="btn btn-warning btn-sm me-2 text-white" id="prevBtn{{ $ebook->id }}">
                                    <i class="fas fa-arrow-left me-1"></i> Sebelumnya
                                </button>
                                <button type="button" class="btn btn-info btn-sm text-white" id="nextBtn{{ $ebook->id }}">
                                    Selanjutnya <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                            <button type="button" class="btn btn-secondary text-white btn-sm" data-bs-dismiss="modal">Tutup</button>
                            @if($ebook->izin_unduh)
                                <a href="{{ Storage::url($ebook->file_url) }}" class="btn btn-primary btn-sm" download>
                                    <i class="fas fa-download me-1"></i> Unduh
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
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
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Menampilkan {{ $ebooks->firstItem() }} sampai {{ $ebooks->lastItem() }} dari {{ $ebooks->total() }} e-book
        </div>
        <div>
            {{ $ebooks->withQueryString()->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
    @endif
</div>

@push('styles')
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
    
    .badge {
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://unpkg.com/epubjs@latest/dist/epub.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize EPUB readers when modals are shown
        @foreach($ebooks as $ebook)
            @if($ebook->file_type === 'epub')
                $('#readEpubModal{{ $ebook->id }}').on('shown.bs.modal', function() {
                    // Start tracking reading session
                    $.ajax({
                        url: '{{ route("reading.start") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ebook_id: '{{ $ebook->id }}'
                        }
                    });

                    // Initialize EPUB reader
                    window.currentBook = ePub("{{ Storage::url($ebook->file_url) }}");
                    window.currentRendition = window.currentBook.renderTo("epub-viewer-{{ $ebook->id }}", {
                        width: "100%",
                        height: "100%",
                        spread: "none"
                    });

                    window.currentRendition.display();
                    
                    document.getElementById('prevBtn{{ $ebook->id }}').addEventListener('click', function() {
                        window.currentRendition.prev();
                    });

                    document.getElementById('nextBtn{{ $ebook->id }}').addEventListener('click', function() {
                        window.currentRendition.next();
                    });
                });
            @elseif($ebook->file_type === 'pdf')
                $('#readPdfModal{{ $ebook->id }}').on('shown.bs.modal', function() {
                    // Start tracking reading session
                    $.ajax({
                        url: '{{ route("reading.start") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ebook_id: '{{ $ebook->id }}'
                        }
                    });
                });
            @endif
        @endforeach
        
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