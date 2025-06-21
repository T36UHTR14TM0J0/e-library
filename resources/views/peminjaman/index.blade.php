@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-filter-square me-2"></i> Filter Peminjaman</h5>
                @if(request()->hasAny(['status', 'search', 'date_from', 'date_to']))
                <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('peminjaman.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
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
                    
                    <div class="col-md-3">
                        <label for="search" class="form-label">Cari</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" id="search" class="form-control form-control-sm" 
                                   placeholder="Judul/Peminjam..." value="{{ request('search') }}">
                            <button class="btn btn-dark" type="submit">
                                <i class="icon-search"></i>
                            </button>
                        </div>
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
    
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50" class="bg-primary text-center text-white">#</th>
                            <th class="bg-primary text-center text-white">Judul Buku</th>
                            <th class="bg-primary text-center text-white">Peminjam</th>
                            <th class="bg-primary text-center text-white">Tanggal Pinjam</th>
                            <th class="bg-primary text-center text-white">Jatuh Tempo</th>
                            <th class="bg-primary text-center text-white">Status</th>
                            <th class="bg-primary text-center text-white" width="180">Aksi</th>
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
                            <td>{{ $peminjaman->user->nama_lengkap }}</td>
                            <td class="text-center">{{ $peminjaman->tanggal_pinjam->locale('id')->translatedFormat('d F Y') }}</td>
                            <td class="text-center @if($peminjaman->isLate()) text-danger fw-bold @endif">
                                {{ $peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('d F Y') }}
                                @if($peminjaman->isLate())
                                    <div class="badge bg-danger mt-1">Terlambat </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $badgeClass = [
                                        'menunggu'      => 'bg-warning',
                                        'dipinjam'      => 'bg-info'
                                    ][$peminjaman->status];
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-info btn-sm text-white" href="{{ route('peminjaman.show',$peminjaman->id) }}" >Detail</a>
                                    @if($peminjaman->status == 'menunggu')
                                        @if(auth()->user()->isAdmin())
                                            <form action="{{ route('admin.peminjaman.approve', $peminjaman->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success text-white" title="Setujui">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.peminjaman.reject', $peminjaman->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger text-white" title="Tolak">
                                                    Tolak
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('peminjaman.cancel', $peminjaman->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger text-white" 
                                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan peminjaman ini?')"
                                                    title="Batalkan">
                                                    Batal
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    @if(auth()->user()->isAdmin() && $peminjaman->status == 'dipinjam')
                                        <button type="button" class="btn btn-sm btn-secondary text-white" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#returnModal{{ $peminjaman->id }}"
                                            title="Konfirmasi Pengembalian">
                                            Dikembalikan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Konfirmasi Pengembalian -->
                        @if(auth()->user()->isAdmin() && $peminjaman->status == 'dipinjam')
                        <div class="modal fade" id="returnModal{{ $peminjaman->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Konfirmasi Pengembalian</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.peminjaman.return', $peminjaman->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <p>Konfirmasi bahwa buku <strong>{{ $peminjaman->buku->judul }}</strong> telah dikembalikan oleh <strong>{{ $peminjaman->user->name }}</strong>?</p>
                                            <div class="mb-3">
                                                <label for="return_notes{{ $peminjaman->id }}" class="form-label">Catatan (Opsional)</label>
                                                <textarea class="form-control" id="return_notes{{ $peminjaman->id }}" name="return_notes" rows="3" placeholder="Masukkan catatan pengembalian..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm text-white btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-sm btn-primary">Konfirmasi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">Belum ada riwayat peminjaman</h4>
                                    <p class="text-muted">Silahkan pinjam buku dari katalog kami</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-light">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted mb-2 mb-md-0">
                    Menampilkan <strong>{{ $peminjamans->firstItem() }}</strong> sampai <strong>{{ $peminjamans->lastItem() }}</strong> dari <strong>{{ $peminjamans->total() }}</strong> entri
                </div>
                <div>
                    {{ $peminjamans->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection