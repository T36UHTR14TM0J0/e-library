@extends('layouts.app')
@section('title', 'Data Komentar/Ulasan')

@section('content')
<div class="container-fluid px-4">
    <div class="row my-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-filter-square me-2"></i> Filter Komentar/Ulasan</h5>
                        @if(request()->hasAny(['search', 'rating']))
                        <a href="{{ route('reviews.index') }}" class="btn btn-sm btn-light">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reviews.index') }}">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="search" class="form-label">Cari Komentar/Ulasan</label>
                                <input type="text" name="search" id="search" class="form-control form-control-sm" 
                                    placeholder="Nama pengguna atau isi komentar..." 
                                    value="{{ request('search') }}">
                                <small class="text-muted">Anda bisa mencari berdasarkan nama pengguna atau isi komentar</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="rating" class="form-label">Filter Rating</label>
                                <select name="rating" id="rating" class="form-select">
                                    <option value="">Semua Rating</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                            {{ $i }} Bintang
                                        </option>
                                    @endfor
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
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <h5 class="mb-0"><i class="bi bi-chat-square-text me-2"></i> Daftar Komentar/Ulasan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" style="font-size: 0.875rem">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center bg-primary text-white" width="5%">No</th>
                                    <th class="text-center bg-primary text-white">Pengguna</th>
                                    <th class="text-center bg-primary text-white">Komentar</th>
                                    <th class="text-center bg-primary text-white" width="15%">Rating</th>
                                    <th class="text-center bg-primary text-white">Tanggal</th>
                                    <th class="text-center bg-primary text-white" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reviews as $review)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($reviews->currentPage() - 1) * $reviews->perPage() }}</td>
                                    <td>{{ $review->user->nama_lengkap }}</td>
                                    <td>{{ Str::limit($review->comment, 50) }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-warning"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-2 small">{{ $review->rating }}/5</span>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $review->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            {{-- @if(auth()->id() == $review->user_id || auth()->user()->isAdmin()) --}}
                                            <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm btn-warning text-white" 
                                               data-bs-toggle="tooltip" title="Edit">
                                                Edit
                                            </a>
                                            <button class="btn btn-sm btn-danger text-white" onclick="confirmDelete('{{ $review->id }}')"
                                               data-bs-toggle="tooltip" title="Hapus">
                                                Hapus
                                            </button>
                                            <form id="delete-form-{{ $review->id }}" action="{{ route('reviews.destroy', $review->id) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            {{-- @endif --}}
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-chat-square-text me-2"></i> Tidak ada data komentar/ulasan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Menampilkan <strong>{{ $reviews->firstItem() }}</strong> sampai <strong>{{ $reviews->lastItem() }}</strong> 
                            dari <strong>{{ $reviews->total() }}</strong> entri
                        </div>
                        <div>
                            {{ $reviews->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>    
    // Enable tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
@endsection