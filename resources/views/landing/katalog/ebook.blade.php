@extends('landing.app')
@section('title', 'Koleksi Ebook')
@section('content')
<section class="py-5 mt-5">
    <div class="container">
        <!-- Header Section -->
        <div class="text-center mb-6 mt-5">
            <h1 class="display-5 fw-bold text-gradient">Koleksi Ebook</h1>
            <p class="lead text-muted">Akses ribuan buku digital kapan saja, di mana saja</p>
        </div>

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-4">
                <form action="{{ route('ebook') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <!-- Combined Search Field -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase text-muted">Cari Ebook</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" 
                                      class="form-control border-start-0" 
                                      name="search" 
                                      value="{{ request('search') }}" 
                                      placeholder="Judul, penulis, kategori, atau prodi...">
                            </div>
                        </div>
                        
                        <!-- Sorting Dropdown -->
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-uppercase text-muted">Urutkan</label>
                            <select class="form-select" name="sort">
                                <option value="judul" {{ request('sort') == 'judul' ? 'selected' : '' }}>A-Z</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Paling Banyak Dibaca</option>
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Terbaru</option>
                            </select>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                                Cari
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
                                <th class="border-0">Judul Ebook</th>
                                <th class="border-0">Penulis</th>
                                <th class="border-0">Kategori</th>
                                <th class="border-0">Prodi</th>
                                <th class="border-0 text-center">Format</th>
                                <th class="border-0 text-center">Akses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ebooks as $index => $ebook)
                            <tr class="border-top">
                                <td class="ps-4 text-muted">{{ $index + $ebooks->firstItem() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($ebook->gambar_sampul)
                                            <img src="{{ asset('storage/' . $ebook->gambar_sampul) }}" 
                                                 class="rounded me-3 shadow-sm" width="45" height="65" 
                                                 style="object-fit: cover;" alt="{{ $ebook->judul }}">
                                        @else
                                            <div class="rounded bg-light me-3 d-flex align-items-center justify-content-center shadow-sm" 
                                                 style="width: 45px; height: 65px;">
                                                <i class="fas fa-file-pdf text-danger"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $ebook->judul }}</h6>
                                            @if($ebook->penerbit)
                                                <small class="text-muted">{{ $ebook->penerbit->nama }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $ebook->penulis }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-opacity-10 bg-dark text-dark">
                                        {{ $ebook->kategori->nama ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $ebook->prodi->nama ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger">
                                        PDF
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($ebook->izin_unduh)
                                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-download me-1"></i> Bebas
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning">
                                            <i class="fas fa-lock me-1"></i> Terbatas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination and Results Info -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4 border-top">
                    <div class="text-muted mb-3 mb-md-0">
                        Menampilkan <span class="fw-bold">{{ $ebooks->firstItem() }}</span> sampai <span class="fw-bold">{{ $ebooks->lastItem() }}</span> 
                        dari <span class="fw-bold">{{ $ebooks->total() }}</span> ebook
                    </div>
                    <div>
                        {{ $ebooks->withQueryString()->onEachSide(1)->links() }}
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
        background-color: #10b981;
    }
</style>

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