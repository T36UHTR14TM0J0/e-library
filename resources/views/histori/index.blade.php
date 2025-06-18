@extends('layouts.app')

@section('title', 'Riwayat Peminjaman & Baca')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-filter-square me-2"></i> Filter Riwayat</h5>
                @if(request()->hasAny(['type', 'status', 'date_from', 'date_to']))
                <a href="{{ route('histori.index') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('histori.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="type" class="form-label">Tipe Riwayat</label>
                        <select name="type" id="type" class="form-select form-select-sm">
                            <option value="">Semua Tipe</option>
                            <option value="peminjaman" {{ request('type') == 'peminjaman' ? 'selected' : '' }}>Peminjaman Buku</option>
                            <option value="ebook" {{ request('type') == 'ebook' ? 'selected' : '' }}>Baca Ebook</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">Dari Tanggal</label>
                        <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" 
                               value="{{ request('date_from') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">Sampai Tanggal</label>
                        <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" 
                               value="{{ request('date_to') }}">
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
    
    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-4" id="historyTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request('type') != 'ebook' ? 'active' : '' }}" id="peminjaman-tab" data-bs-toggle="tab" data-bs-target="#peminjaman" type="button" role="tab">
                <i class="bi bi-book me-1"></i> Peminjaman Buku
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request('type') == 'ebook' ? 'active' : '' }}" id="ebook-tab" data-bs-toggle="tab" data-bs-target="#ebook" type="button" role="tab">
                <i class="bi bi-file-earmark-text me-1"></i> Baca Ebook
            </button>
        </li>
    </ul>
    
    <!-- Tab Content -->
    <div class="tab-content" id="historyTabsContent">
        <!-- Peminjaman Tab -->
        <div class="tab-pane fade {{ request('type') != 'ebook' ? 'show active' : '' }}" id="peminjaman" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50" class="bg-primary text-center text-white">#</th>
                                    <th class="bg-primary text-white">Judul Buku</th>
                                    <th class="bg-primary text-center text-white">Tanggal Pinjam</th>
                                    <th class="bg-primary text-center text-white">Jatuh Tempo</th>
                                    <th class="bg-primary text-center text-white">Tanggal Kembali</th>
                                    <th class="bg-primary text-center text-white">Status</th>
                                    <th class="bg-primary text-center text-white" width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peminjamans as $peminjaman)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $loop->iteration + ($peminjamans->currentPage() - 1) * $peminjamans->perPage() }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($peminjaman->buku->gambar_sampul)
                                                <img src="{{ asset('storage/'.$peminjaman->buku->gambar_sampul) }}" 
                                                     alt="{{ $peminjaman->buku->judul }}" 
                                                     class="img-thumbnail me-3" 
                                                     style="width: 50px; height: 70px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 50px; height: 70px;">
                                                    <i class="fas fa-book text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $peminjaman->buku->judul }}</h6>
                                                <small class="text-muted">{{ $peminjaman->buku->penulis }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $peminjaman->tanggal_pinjam->locale('id')->translatedFormat('d F Y') }}</td>
                                    <td class="text-center @if($peminjaman->isLate()) text-danger fw-bold @endif">
                                        {{ $peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('d F Y') }}
                                        @if($peminjaman->isLate())
                                            <div class="badge bg-danger mt-1">Terlambat</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->locale('id')->translatedFormat('d F Y') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $badgeClass = [
                                                'menunggu'      => 'bg-warning',
                                                'dipinjam'      => 'bg-info',
                                                'dikembalikan'  => 'bg-success',
                                                'dibatalkan'    => 'bg-danger'
                                            ][$peminjaman->status];
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($peminjaman->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                       <button class="btn btn-sm btn-info text-white" 
                                              data-bs-toggle="modal" 
                                              data-bs-target="#detailModal{{ $peminjaman->id }}"
                                              title="Quick View">
                                          Detail
                                      </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="py-5">
                                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                            <h4 class="text-muted">Belum ada riwayat peminjaman</h4>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($peminjamans->hasPages())
                <div class="card-footer bg-light">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <div class="text-muted mb-2 mb-md-0">
                            Menampilkan <strong>{{ $peminjamans->firstItem() }}</strong> sampai <strong>{{ $peminjamans->lastItem() }}</strong> dari <strong>{{ $peminjamans->total() }}</strong> entri
                        </div>
                        <div>
                            {{ $peminjamans->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Ebook Tab -->
        <div class="tab-pane fade {{ request('type') == 'ebook' ? 'show active' : '' }}" id="ebook" role="tabpanel">
            <div class="row">
                @forelse ($ebookReadings as $reading)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $reading->ebook->gambar_sampul) ?? asset('images/default-cover.jpg') }}" 
                                 class="card-img-top" 
                                 alt="Cover Ebook" 
                                 style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 bg-primary text-white px-2 py-1 small">
                                <i class="bi bi-clock-history me-1"></i> {{ $reading->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $reading->ebook->judul ?? 'Ebook telah dihapus' }}</h5>
                            <p class="card-text text-muted small mb-2">
                                <i class="bi bi-person me-1"></i> {{ $reading->ebook->penulis ?? '-' }}
                            </p>
                            <p class="card-text text-muted small">
                                <i class="bi bi-calendar me-1"></i> Dibaca pada: {{ $reading->created_at->locale('id')->translatedFormat('d F Y H:i') }}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ $reading->ebook ? route('ebook.show', $reading->ebook->id) : '#' }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-book me-1"></i> Baca Lagi
                                </a>
                                <span class="badge bg-secondary">
                                    {{ $reading->ebook->kategori->nama ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Belum ada riwayat baca ebook</h4>
                            <p class="text-muted">Mulai baca ebook dari koleksi kami</p>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
            
            @if($ebookReadings->hasPages())
            <div class="mt-4">
                <div class="d-flex justify-content-center">
                    {{ $ebookReadings->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Detail Peminjaman -->
@foreach($peminjamans as $peminjaman)
<div class="modal fade" id="detailModal{{ $peminjaman->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detail Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        @if($peminjaman->buku->gambar_sampul)
                            <img src="{{ asset('storage/'.$peminjaman->buku->gambar_sampul) }}" 
                                 class="img-fluid rounded" 
                                 alt="{{ $peminjaman->buku->judul }}">
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-book fa-3x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h4>{{ $peminjaman->buku->judul }}</h4>
                        <p class="text-muted">{{ $peminjaman->buku->penulis }}</p>
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Tanggal Pinjam:</strong><br>
                                {{ $peminjaman->tanggal_pinjam->locale('id')->translatedFormat('d F Y') }}</p>
                                
                                <p><strong>Jatuh Tempo:</strong><br>
                                <span class="{{ $peminjaman->isLate() ? 'text-danger fw-bold' : '' }}">
                                    {{ $peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('d F Y') }}
                                    @if($peminjaman->isLate())
                                        <span class="badge bg-danger">Terlambat</span>
                                    @endif
                                </span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Tanggal Kembali:</strong><br>
                                {{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->locale('id')->translatedFormat('d F Y') : '-' }}</p>
                                
                                <p><strong>Status:</strong><br>
                                @php
                                    $badgeClass = [
                                        'menunggu'      => 'bg-warning',
                                        'dipinjam'      => 'bg-info',
                                        'dikembalikan'  => 'bg-success',
                                        'dibatalkan'    => 'bg-danger'
                                    ][$peminjaman->status];
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($peminjaman->status) }}</span></p>
                            </div>
                        </div>
                        
                        @if($peminjaman->denda > 0)
                        <div class="alert alert-warning mt-3">
                            <h6><i class="bi bi-exclamation-triangle-fill me-2"></i> Denda</h6>
                            <p class="mb-0">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="bi bi-info-circle me-2"></i> Informasi Tambahan</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Disetujui Oleh:</span>
                                <span>{{ $peminjaman->disetujuiOleh->name ?? '-' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Tanggal Disetujui:</span>
                                <span>{{ $peminjaman->tanggal_setujui ? $peminjaman->tanggal_setujui->locale('id')->translatedFormat('d F Y H:i') : '-' }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="bi bi-chat-left-text me-2"></i> Catatan</h5>
                        <div class="card">
                            <div class="card-body">
                                @if($peminjaman->catatan_pinjam)
                                    <p><strong>Catatan Peminjaman:</strong><br>
                                    {{ $peminjaman->catatan_pinjam }}</p>
                                @endif
                                
                                @if($peminjaman->catatan_kembali)
                                    <p><strong>Catatan Pengembalian:</strong><br>
                                    {{ $peminjaman->catatan_kembali }}</p>
                                @endif
                                
                                @if($peminjaman->catatan_batal)
                                    <p><strong>Catatan Pembatalan:</strong><br>
                                    {{ $peminjaman->catatan_batal }}</p>
                                @endif
                                
                                @if(!$peminjaman->catatan_pinjam && !$peminjaman->catatan_kembali && !$peminjaman->catatan_batal)
                                    <p class="text-muted">Tidak ada catatan</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('styles')
<style>
    .nav-tabs .nav-link {
        font-weight: 500;
        border: none;
        padding: 0.75rem 1.5rem;
        color: #6c757d;
    }
    
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        background-color: transparent;
        border-bottom: 3px solid #0d6efd;
    }
    
    .nav-tabs .nav-link:hover:not(.active) {
        border-bottom: 3px solid #dee2e6;
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
</style>
@endpush

@push('scripts')
<script>
    // Aktifkan tab berdasarkan URL parameter
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const typeParam = urlParams.get('type');
        
        if(typeParam === 'ebook') {
            const ebookTab = new bootstrap.Tab(document.getElementById('ebook-tab'));
            ebookTab.show();
        }
    });
</script>
@endpush