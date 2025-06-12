@extends('layouts.app')
@section('title','Laporan Peminjaman')
@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-filter-square me-2"></i> Filter Laporan Peminjaman</h5>
                @if(request()->hasAny(['status', 'start_date', 'end_date']))
                <a href="{{ route('laporan.peminjaman') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.peminjaman') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status Peminjaman</label>
                        <select name="status" id="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" 
                               value="{{ request('start_date') }}">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" 
                               value="{{ request('end_date') }}">
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <button type="submit" class="btn btn-sm btn-dark">
                        <i class="bi bi-funnel me-1"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('laporan.peminjaman.export.pdf', request()->query()) }}" class="btn btn-sm text-white btn-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                    </a>
                    <a href="{{ route('laporan.peminjaman.export.excel', request()->query()) }}" class="btn btn-sm text-white btn-success">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white border-bottom py-3">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i> Data Peminjaman</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50" class="bg-primary text-center text-white">#</th>
                            <th class="bg-primary text-white">Peminjam</th>
                            <th class="bg-primary text-white">Buku</th>
                            <th class="bg-primary text-center text-white">Tanggal Pinjam</th>
                            <th class="bg-primary text-center text-white">Jatuh Tempo</th>
                            <th class="bg-primary text-center text-white">Tanggal Kembali</th>
                            <th class="bg-primary text-center text-white">Status</th>
                            <th class="bg-primary text-center text-white">Denda</th>
                            <th class="bg-primary text-center text-white">Disetujui Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $key => $peminjaman)
                        <tr class="align-middle">
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $peminjaman->user->nama_lengkap }}</td>
                            <td>
                                @if($peminjaman->buku)
                                    <div class="d-flex align-items-center">
                                        @if($peminjaman->buku->gambar_sampul)
                                            <img src="{{ asset('storage/'.$peminjaman->buku->gambar_sampul) }}" 
                                                 alt="{{ $peminjaman->buku->judul }}" 
                                                 class="img-thumbnail me-3" 
                                                 style="width: 40px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 60px;">
                                                <i class="fas fa-book text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $peminjaman->buku->judul }}</h6>
                                            <small class="text-muted">{{ $peminjaman->buku->penulis }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-danger">Buku dihapus</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $peminjaman->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                            <td class="text-center">
                                @php
                                    $badgeClass = [
                                        'menunggu' => 'bg-warning',
                                        'dipinjam' => 'bg-primary',
                                        'dikembalikan' => 'bg-success',
                                        'dibatalkan' => 'bg-danger'
                                    ][$peminjaman->status];
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </td>
                            <td class="text-center @if($peminjaman->denda > 0) text-danger fw-bold @endif">
                                Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}
                            </td>
                            <td class="text-center">{{ $peminjaman->disetujui ? $peminjaman->disetujui->username : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">Tidak ada data peminjaman</h4>
                                    <p class="text-muted">Coba gunakan filter yang berbeda</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="6" class="text-end">Total</th>
                            <th class="text-center">{{ $peminjamans->count() }} Peminjaman</th>
                            <th class="text-center">Rp {{ number_format($peminjamans->sum('denda'), 0, ',', '.') }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        {{-- @if($peminjamans->hasPages()) --}}
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
        {{-- @endif --}}
    </div>
</div>
@endsection