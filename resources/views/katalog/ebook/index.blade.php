@extends('layouts.app')
@section('title', 'Katalog E-Book')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 mb-5">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filter">
                <i class="icon-search me-1"></i> Filter
            </button>
            @if(request()->hasAny(['judul', 'penulis', 'kategori_id', 'prodi_id', 'izin_unduh']))
            <a href="{{ route('KatalogEbook.index') }}" class="btn btn-sm btn-outline-danger">
                <i class="icon-close me-1"></i> Reset Filter
            </a>
            @endif
        </div>
                
        <div class="col-md-12">
            @if($ebooks->isEmpty())
            <div class="text-center py-4">
                <img src="{{ asset('assets/images/default-cover.png') }}" alt="No data" style="height: 150px;">
                <h5 class="mt-3">Tidak ada e-book tersedia</h5>
            </div>
            @else
            <div class="row g-4 px-3">
                @foreach ($ebooks as $ebook)
                <div class="col-md-4">
                    <div class="card card-hover h-100">
                        <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                            <div class="d-block blur-shadow-image">
                                <img src="{{ $ebook->cover_url ?? asset('assets/images/default-cover.png') }}" 
                                    alt="{{ $ebook->judul }}" 
                                    class="img-fluid border-radius-lg" 
                                    style="height: 200px; width: 100%; object-fit: cover;">
                            </div>
                            <div class="colored-shadow" 
                                style="background-image: url('{{ $ebook->cover_url ?? asset('assets/images/ebook-default.jpg') }}');"></div>
                        </div>
                        <div class="card-body pt-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-gradient-{{ $ebook->izin_unduh ? 'success' : 'danger' }}">
                                    {{ $ebook->izin_unduh ? 'Boleh Unduh' : 'Tidak Boleh Unduh' }}
                                </span>
                            </div>
                            <h5 class="font-weight-normal">
                                <a href="{{ route('ebook.show', $ebook->id) }}" class="text-dark">{{ Str::limit($ebook->judul, 50) }}</a>
                            </h5>
                            <p class="mb-0 text-sm">
                                <i class="fas fa-user-edit me-1"></i> {{ $ebook->penulis }}
                            </p>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-sm">
                                    <i class="icon-tag me-1"></i> {{ $ebook->kategori->nama ?? '-' }}
                                </span>
                                <span class="text-sm">
                                    {{ $ebook->prodi->nama ?? '-' }}
                                </span>
                            </div>
                            <hr class="horizontal dark my-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="{{ route('ebook.show', $ebook->id) }}" class="btn btn-sm btn-outline-info">
                                        Detail
                                    </a>
                                    @if($ebook->file_url)
                                        @if($ebook->file_type === 'pdf')
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#readPdfModal{{ $ebook->id }}">
                                                Baca PDF
                                            </button>
                                        @elseif($ebook->file_type === 'epub')
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#readEpubModal{{ $ebook->id }}">
                                                Baca EPUB
                                            </button>
                                        @endif
                                    @endif
                                </div>
                                <small class="text-muted">
                                    {{ $ebook->pengunggah->nama_lengkap ?? '-' }}
                                </small>
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
                                            src="{{ Storage::url($ebook->file_url) }}?file={{ urlencode($ebook->file_url)}}" 
                                            frameborder="0" 
                                            style="width: 100%; height: 100%;"
                                        ></iframe>
                                    @else
                                        <!-- Untuk file lokal -->
                                        <iframe 
                                            src="{{ Storage::url($ebook->file_url) }}#toolbar=0&navpanes=0" 
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
                                    <a href="{{ $ebook->file_url }}" class="btn btn-primary btn-sm" download>
                                        Unduh
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
                                        Sebelumnya
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm text-white" id="nextBtn{{ $ebook->id }}">
                                        Selanjutnya
                                    </button>
                                </div>
                                <button type="button" class="btn btn-secondary text-white btn-sm" data-bs-dismiss="modal">Tutup</button>
                                @if($ebook->izin_unduh)
                                    <a href="{{ $ebook->file_url }}" class="btn btn-primary btn-sm" download>
                                        Unduh
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @push('scripts')
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
                <script src="https://unpkg.com/epubjs@latest/dist/epub.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        $('#readEpubModal{{ $ebook->id }}').on('shown.bs.modal', function() {
                            var viewer = document.getElementById("epub-viewer-{{ $ebook->id }}");
                            viewer.innerHTML = '';
                            
                            try {
                                var book = ePub("{{ Storage::url($ebook->file_url) }}");
                                var rendition = book.renderTo("epub-viewer-{{ $ebook->id }}", {
                                    width: "100%",
                                    height: "100%",
                                    spread: "none",
                                    manager: "continuous"
                                });
                                
                                rendition.display().then(function() {
                                    // Connect buttons to navigation
                                    document.getElementById('prevBtn{{ $ebook->id }}').addEventListener('click', function() {
                                        rendition.prev();
                                    });
                                    
                                    document.getElementById('nextBtn{{ $ebook->id }}').addEventListener('click', function() {
                                        rendition.next();
                                    });
                                    
                                    // Keyboard navigation
                                    document.addEventListener('keydown', function(e) {
                                        if (e.keyCode === 37) { // Left arrow
                                            rendition.prev();
                                        } else if (e.keyCode === 39) { // Right arrow
                                            rendition.next();
                                        }
                                    });
                                });
                                
                                book.on('error', function(error) {
                                    console.error("EPUB Error:", error);
                                    viewer.innerHTML = '<div class="alert alert-danger">Gagal memuat file EPUB. Silakan coba lagi.</div>';
                                });
                                
                                
                            } catch (error) {
                                console.error("EPUB Initialization Error:", error);
                                viewer.innerHTML = '<div class="alert alert-danger">Gagal memulai pembaca EPUB.</div>';
                            }
                        });
                    });
                </script>
                @endpush
                @endif
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center px-4 mt-4">
                <div class="text-muted">
                    Menampilkan {{ $ebooks->firstItem() }} sampai {{ $ebooks->lastItem() }} dari {{ $ebooks->total() }} e-book
                </div>
                <div>
                    {{ $ebooks->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filter" tabindex="-1" aria-labelledby="filterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="GET" action="{{ route('KatalogEbook.index') }}">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="filterLabel">
                        <i class="icon-search me-2"></i>Filter E-Book
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul E-Book</label>
                        <input type="text" name="judul" id="judul" class="form-control" 
                            placeholder="Cari berdasarkan judul" value="{{ request('judul') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="penulis" class="form-label">Penulis</label>
                        <input type="text" name="penulis" id="penulis" class="form-control" 
                            placeholder="Cari berdasarkan penulis" value="{{ request('penulis') }}">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="prodi_id" class="form-label">Program Studi</label>
                            <select name="prodi_id" id="prodi_id" class="form-select">
                                <option value="">Semua Prodi</option>
                                @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="izin_unduh" class="form-label">Status Unduh</label>
                        <select name="izin_unduh" id="izin_unduh" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('izin_unduh') == '1' ? 'selected' : '' }}>Diizinkan</option>
                            <option value="0" {{ request('izin_unduh') == '0' ? 'selected' : '' }}>Tidak Diizinkan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary text-white">
                        Reset
                    </button>
                    <button type="submit" class="btn bg-primary text-white">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .modal-fullscreen {
        min-width: 100%;
    }
    .modal-xl {
        max-width: 95%;
    }
</style>
@endpush
@endsection