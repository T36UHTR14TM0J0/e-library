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
                        @if(request()->hasAny(['search', 'sort']))
                        <a href="{{ route('KatalogBuku.index') }}" class="btn btn-sm btn-light">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('KatalogBuku.index') }}">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="search" class="form-label">Cari Buku</label>
                                    <input type="text" name="search" id="search" class="form-control form-control-sm" 
                                           placeholder="Judul, penulis,kategori,prodi, atau penerbit..." 
                                           value="{{ request('search') }}">
                                <small class="text-muted">Anda bisa mencari berdasarkan judul, penulis, kategori, prodi, atau penerbit buku</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="sort" class="form-label">Urutkan</label>
                                <select name="sort" id="sort" class="form-select">
                                    <option value="">Default</option>
                                    <option value="favorit" {{ request('sort') == 'favorit' ? 'selected' : '' }}>Paling Favorit</option>
                                    <option value="sering_dipinjam" {{ request('sort') == 'sering_dipinjam' ? 'selected' : '' }}>Sering Dipinjam</option>
                                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-dark">
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
                <h5 class="mt-3">Tidak ada buku yang sesuai dengan kriteria pencarian</h5>
                <p class="text-muted">Coba gunakan kata kunci atau filter yang berbeda</p>
            </div>
            @else
            <div class="row g-4 px-3">
                @foreach ($bukus as $buku)
                <div class="col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-header p-0 mx-2 mt-2 position-relative">
                            <img src="{{ asset($buku->gambar_sampul ? 'storage/' . $buku->gambar_sampul : 'assets/images/default-cover.png') }}" 
                                alt="{{ $buku->judul ?? 'Book Cover' }}" 
                                class="img-fluid rounded" 
                                style="height: 150px; width: 100%; object-fit: cover;"
                                onerror="this.src='{{ asset('assets/images/default-cover.png') }}'">
                        </div>
                        <div class="card-body pt-2 px-2 pb-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-danger small">{{ Str::limit($buku->prodi->nama ?? '-', 10) }}</span>
                                <span class="badge bg-info small">{{ $buku->jumlahTersedia() }}</span>
                            </div>
                            <h6 class="card-title mb-1">
                                <a href="#" class="text-dark">{{ Str::limit($buku->judul, 30) }}</a>
                            </h6>
                            <p class="mb-1 small text-muted">
                                <i class="fas fa-user-edit me-1"></i> {{ Str::limit($buku->penulis, 20) }}
                            </p>
                            <div class="d-flex justify-content-between mt-1">
                                <span class="badge bg-secondary small">{{ Str::limit($buku->kategori->nama ?? '-', 10) }}</span>
                                <span class="badge bg-success small">{{ $buku->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white px-2 py-1">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('KatalogBuku.show', $buku->id) }}" class="btn btn-sm btn-outline-info py-0 px-2">
                                    Detail
                                </a>
                                @if($buku->tersedia() > 0)
                                <button type="button" class="btn btn-sm btn-outline-success py-0 px-2" data-bs-toggle="modal" data-bs-target="#pinjamModal-{{ $buku->id }}">
                                    Pinjam
                                </button>
                                @else
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2" disabled>
                                    Habis
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
                    {{ $bukus->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

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

</script>
@endpush
@endsection