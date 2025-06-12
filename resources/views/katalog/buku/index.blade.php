@extends('layouts.app')
@section('title', 'Katalog Buku')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-filter-square me-2"></i> Filter Buku</h5>
                        @if(request()->hasAny(['judul', 'penulis', 'kategori_id', 'prodi_id']))
                        <a href="{{ route('KatalogBuku.index') }}" class="btn btn-sm btn-light">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('KatalogBuku.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="judul" class="form-label">Judul Buku</label>
                                <input type="text" name="judul" id="judul" class="form-control form-control-sm" 
                                       placeholder="Cari berdasarkan judul" value="{{ request('judul') }}">
                            </div>
                            
                            <div class="col-md-4">
                                <label for="penulis" class="form-label">Penulis</label>
                                <input type="text" name="penulis" id="penulis" class="form-control form-control-sm" 
                                       placeholder="Cari berdasarkan penulis" value="{{ request('penulis') }}">
                            </div>
                            
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
                                <label for="prodi_id" class="form-label">Program Studi</label>
                                <select name="prodi_id" id="prodi_id" class="form-select form-select-sm">
                                    <option value="">Semua Prodi</option>
                                    @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-sm btn-dark">
                                <i class="bi bi-funnel me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                
        <div class="col-md-12">
            @if($bukus->isEmpty())
            <div class="text-center py-4">
                <img src="{{ asset('assets/images/default-cover.png') }}" alt="No data" style="height: 150px;">
                <h5 class="mt-3">Tidak ada Buku tersedia</h5>
            </div>
            @else
            <div class="row g-4 px-3">
                @foreach ($bukus as $buku)
                <div class="col-md-4">
                    <div class="card card-hover h-100">
                       <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                            <div class="d-block blur-shadow-image">
                                <img src="{{ asset($buku->gambar_sampul ? 'storage/' . $buku->gambar_sampul : 'assets/images/default-cover.png') }}" 
                                    alt="{{ $buku->judul ?? 'Book Cover' }}" 
                                    class="img-fluid border-radius-lg" 
                                    style="height: 200px; width: 100%; object-fit: cover;"
                                    onerror="this.src='{{ asset('assets/images/default-cover.png') }}'">
                            </div>
                            <div class="colored-shadow" 
                                style="background-image: url('{{ asset($buku->gambar_sampul ? 'storage/' . $buku->gambar_sampul : 'assets/images/default-cover.png') }}');"></div>
                        </div>
                        <div class="card-body pt-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-gradient-danger">
                                    {{ $buku->prodi->nama ?? '-' }}
                                </span>
                                <span class="badge bg-info">
                                    Tersedia : {{ $buku->jumlahTersedia() }}
                                </span>
                            </div>
                            <h5 class="font-weight-normal">
                                <a href="#" class="text-dark">{{ Str::limit($buku->judul, 50) }}</a>
                            </h5>
                            <p class="mb-0 text-sm">
                                <i class="fas fa-user-edit me-1"></i> {{ $buku->penulis }}
                            </p>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="badge bg-secondary">
                                    {{ $buku->kategori->nama ?? '-' }}
                                </span>
                                <span class="badge bg-success">
                                    {{ $buku->created_at->locale('id')->translatedFormat('d F Y H:i') ?? '-' }}
                                </span>
                            </div>
                            <hr class="horizontal dark my-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="{{ route('KatalogBuku.show', $buku->id) }}" class="btn btn-sm btn-outline-info">
                                        Detail
                                    </a>
                                </div>
                                @if($buku->tersedia() > 0)
                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#pinjamModal-{{ $buku->id }}">
                                    Pinjam
                                </button>
                                @else
                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                    Tidak Tersedia
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pinjam Modal for each book -->
                <div class="modal fade" id="pinjamModal-{{ $buku->id }}" tabindex="-1" aria-labelledby="pinjamModalLabel-{{ $buku->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('peminjaman.store') }}" method="POST">
                                @csrf
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="pinjamModalLabel-{{ $buku->id }}">Pinjam Buku</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                                    <div class="mb-3">
                                        <label class="form-label">Judul Buku</label>
                                        <input type="text" class="form-control" value="{{ $buku->judul }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Penulis</label>
                                        <input type="text" class="form-control" value="{{ $buku->penulis }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Pinjam</label>
                                        <input type="date" class="form-control" id="tanggal_pinjam-{{ $buku->id }}" name="tanggal_pinjam" value="{{ now()->format('Y-m-d') }}" onchange="calculateDueDate({{ $buku->id }})">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Jatuh Tempo</label>
                                        <input type="date" class="form-control" id="tanggal_jatuh_tempo-{{ $buku->id }}" name="tanggal_jatuh_tempo">
                                    </div>
                                    <div class="mb-3">
                                        <label for="catatan" class="form-label">Catatan (Opsional)</label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm text-white btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-sm text-white btn-primary">Konfirmasi Pinjam</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center px-4 mt-4">
                <div class="text-muted">
                    Menampilkan {{ $bukus->firstItem() }} sampai {{ $bukus->lastItem() }} dari {{ $bukus->total() }} Buku
                </div>
                <div>
                    {{ $bukus->links() }}
                </div>
            </div>
            @endif
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
    .modal-body iframe {
        border: none;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .colored-shadow {
        position: absolute;
        top: 12px;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        filter: blur(12px);
        z-index: -1;
        opacity: 0.5;
    }
</style>
@endpush

@push('scripts')
<script>
    // Calculate due date based on user role when modal is shown
    document.querySelectorAll('[id^="pinjamModal-"]').forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const modalId = this.id.split('-')[1];
            calculateDueDate(modalId);
        });
    });

    // Function to calculate due date
    function calculateDueDate(bookId) {
        const borrowDateInput = document.getElementById(`tanggal_pinjam-${bookId}`);
        const dueDateInput = document.getElementById(`tanggal_jatuh_tempo-${bookId}`);
        
        const borrowDate = new Date(borrowDateInput.value);
        
        // Check user role (you'll need to pass this from your controller)
        const isLecturer = {{ auth()->user()->is_lecturer ? 'true' : 'false' }};
        
        if (isLecturer) {
            // Calculate 1 semester (6 months) for lecturers
            const dueDate = new Date(borrowDate);
            dueDate.setMonth(dueDate.getMonth() + 6);
            dueDateInput.value = formatDate(dueDate);
        } else {
            // Calculate 1 week for students
            const dueDate = new Date(borrowDate);
            dueDate.setDate(dueDate.getDate() + 7);
            dueDateInput.value = formatDate(dueDate);
        }
    }

    // Helper function to format date as YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Initialize due date when page loads (for each modal)
    document.addEventListener('DOMContentLoaded', function() {
        @foreach ($bukus as $buku)
            calculateDueDate({{ $buku->id }});
        @endforeach
    });
</script>
@endpush
@endsection