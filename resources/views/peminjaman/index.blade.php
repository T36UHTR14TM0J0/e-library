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
                                    {{-- <img src="{{ asset($peminjaman->buku->gambar_sampul ? 'storage/'.$peminjaman->buku->gambar_sampul : 'assets/images/default-cover.png') }}" 
                                         alt="{{ $peminjaman->buku->judul }}" 
                                         class="img-thumbnail me-3" 
                                         style="width: 50px; height: 70px; object-fit: cover;"> --}}
                                    <div>
                                        <h6 class="mb-0">{{ $peminjaman->buku->judul }}</h6>
                                        <small class="text-muted">{{ $peminjaman->buku->penulis }}</small>
                                    </div>
                                </div>
                            </td>
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
                                    {{-- <a href="{{ route('peminjaman.show', $peminjaman->id) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a> --}}
                                    
                                    @if($peminjaman->status == 'menunggu')
                                    <form action="#" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger text-white" title="Batalkan">
                                            Batal
                                        </button>
                                    </form>
                                    &nbsp;
                                      @if(auth()->user()->isAdmin())
                                        @if($peminjaman->status == 'menunggu')
                                        <form action="#" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-info text-white" title="konfirmasi">
                                                Konfirmasi
                                            </button>
                                        </form>
                                        @endif
                                      @endif
                                    @endif

                                    @if(auth()->user()->isAdmin())
                                    @if($peminjaman->status == 'dipinjam')
                                    <form action="#" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success text-white" title="dikembalikan">
                                            Dikembalikan
                                        </button>
                                    </form>
                                    @endif
                                    @endif

                                    

                                    {{-- @if(auth()->user()->isAdmin()) --}}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <h5 class="mt-3">Belum ada riwayat peminjaman</h5>
                                <p class="text-muted">Silahkan pinjam buku dari katalog kami</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {{-- @if($peminjamans->hasPages()) --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $peminjamans->firstItem() }} sampai {{ $peminjamans->lastItem() }} dari {{ $peminjamans->total() }} peminjaman
                </div>
                <div>
                    {{ $peminjamans->links() }}
                </div>
            </div>
            {{-- @endif --}}
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
</style>
@endpush