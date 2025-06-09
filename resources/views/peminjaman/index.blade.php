@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Peminjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjamans as $peminjaman)
                        <tr>
                            <td>{{ $loop->iteration + ($peminjamans->currentPage() - 1) * $peminjamans->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- @if($peminjaman->buku->gambar_sampul)
                                        <img src="{{ asset('storage/'.$peminjaman->buku->gambar_sampul) }}" 
                                             alt="{{ $peminjaman->buku->judul }}" 
                                             class="img-thumbnail me-3" 
                                             style="width: 50px; height: 70px; object-fit: cover;">
                                    @endif --}}
                                    <div>
                                        <h6 class="mb-0">{{ $peminjaman->buku->judul }}</h6>
                                        <small class="text-muted">{{ $peminjaman->buku->penulis }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $peminjaman->user->nama_lengkap }}</td>
                            <td>{{ $peminjaman->tanggal_pinjam }}</td>
                            <td @if($peminjaman->isLate()) class="text-danger fw-bold" @endif>
                                {{ $peminjaman->tanggal_jatuh_tempo }}
                                @if($peminjaman->isLate())
                                    <span class="badge bg-danger">Terlambat</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'menunggu' => 'bg-warning',
                                        'dipinjam' => 'bg-primary',
                                        'dikembalikan' => 'bg-success',
                                        'terlambat' => 'bg-danger',
                                        'dibatalkan' => 'bg-secondary'
                                    ][$peminjaman->status];
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if($peminjaman->status == 'menunggu')
                                        @if(auth()->user()->isAdmin())
                                            <form action="{{ route('admin.peminjaman.approve', $peminjaman->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success" title="Setujui Peminjaman">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                            </form>
                                            &nbsp;
                                            <form action="{{ route('admin.peminjaman.reject', $peminjaman->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Tolak Peminjaman">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('peminjaman.cancel', $peminjaman->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Batalkan Peminjaman" 
                                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan peminjaman ini?')">
                                                    <i class="fas fa-times"></i> Batal
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    @if(auth()->user()->isAdmin() && $peminjaman->status == 'dipinjam')
                                        <button type="button" class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#returnModal{{ $peminjaman->id }}"
                                            title="Konfirmasi Pengembalian">
                                            <i class="fas fa-book"></i> Kembalikan
                                        </button>

                                        <!-- Modal Konfirmasi Pengembalian -->
                                        <div class="modal fade" id="returnModal{{ $peminjaman->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Pengembalian</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.peminjaman.return', $peminjaman->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <p>Konfirmasi bahwa buku <strong>{{ $peminjaman->buku->judul }}</strong> telah dikembalikan oleh <strong>{{ $peminjaman->user->name }}</strong>?</p>
                                                            
                                                            <div class="mb-3">
                                                                <label for="return_notes{{ $peminjaman->id }}" class="form-label">Catatan (Opsional)</label>
                                                                <textarea class="form-control" id="return_notes{{ $peminjaman->id }}" name="return_notes" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Konfirmasi Pengembalian</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <h5 class="mt-3">Belum ada riwayat peminjaman</h5>
                                <p class="text-muted">Silahkan pinjam buku dari katalog kami</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $peminjamans->firstItem() }} sampai {{ $peminjamans->lastItem() }} dari {{ $peminjamans->total() }} peminjaman
                </div>
                <div>
                    {{ $peminjamans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .img-thumbnail {
        padding: 0.25rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    .btn-group {
        display: flex;
        flex-wrap: nowrap;
    }
    .btn-group .btn {
        white-space: nowrap;
    }
</style>
@endpush