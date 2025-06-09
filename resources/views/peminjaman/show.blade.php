@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
               
        <div class="card-body">
            <!-- Status Badge -->
            <div class="mb-4">
                @php
                    $statusColors = [
                        'menunggu' => 'bg-secondary',
                        'dipinjam' => 'bg-info',
                        'dikembalikan' => 'bg-success',
                        'terlambat' => 'bg-warning',
                        'dibatalkan' => 'bg-danger'
                    ];
                @endphp
                <span class="badge {{ $statusColors[$peminjaman->status] ?? 'bg-secondary' }} fs-6">
                    Status: {{ ucfirst($peminjaman->status) }}
                </span>
            </div>

            <div class="row">
                <!-- Book Information -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Informasi Buku</h5>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Judul Buku</dt>
                                <dd class="col-sm-8">{{ $peminjaman->buku->judul ?? '-' }}</dd>

                                <dt class="col-sm-4">Kategori</dt>
                                <dd class="col-sm-8">{{ $peminjaman->buku->kategori->nama ?? '-' }}</dd>

                                <dt class="col-sm-4">Program Studi</dt>
                                <dd class="col-sm-8">{{ $peminjaman->buku->prodi->nama ?? '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Borrower Information -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Informasi Peminjam</h5>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                @if($peminjaman->user->role != 'admin')
                                <dt class="col-sm-4">NPM/NIDN</dt>
                                <dd class="col-sm-8">
                                    {{ $peminjaman->user->role == 'mahasiswa' ? $peminjaman->user->npm : ($peminjaman->user->role == 'dosen' ? $peminjaman->user->nidn : '-') }}
                                </dd>
                                @endif

                                <dt class="col-sm-4">Nama Lengkap</dt>
                                <dd class="col-sm-8">{{ $peminjaman->user->nama_lengkap }}</dd>
                                 @if($peminjaman->user->role != 'admin')
                                  <dt class="col-sm-4">Program Studi</dt>
                                  <dd class="col-sm-8">{{ $peminjaman->user->prodi->nama ?? '-' }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Details -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Detail Peminjaman</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Tanggal Pinjam</dt>
                                <dd class="col-sm-7">{{ $peminjaman->tanggal_pinjam->locale('id')->translatedFormat('d F Y') }}</dd>

                                <dt class="col-sm-5">Jatuh Tempo</dt>
                                <dd class="col-sm-7">{{ $peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('d F Y') }}</dd>

                                <dt class="col-sm-5">Lama Pinjam</dt>
                                <dd class="col-sm-7">
                                    {{ $peminjaman->tanggal_pinjam->diffInDays(now()) }} hari
                                    @if($peminjaman->status == 'terlambat')
                                        <span class="badge bg-danger ms-2">Terlambat</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                @if($peminjaman->tanggal_kembali)
                                <dt class="col-sm-5">Tanggal Kembali</dt>
                                <dd class="col-sm-7">{{ $peminjaman->tanggal_kembali->locale('id')->translatedFormat('d F Y') }}</dd>
                                @endif

                                @if($peminjaman->denda > 0)
                                <dt class="col-sm-5">Denda</dt>
                                <dd class="col-sm-7">Rp {{ number_format($peminjaman->denda, 2) }}</dd>
                                @endif

                                @if($peminjaman->disetujui_oleh)
                                <dt class="col-sm-5">Disetujui Oleh</dt>
                                <dd class="col-sm-7">{{ ($peminjaman->disetujui_oleh) ? $peminjaman->disetujui->nama_lengkap : '-' }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if($peminjaman->tanggal_pinjam || $peminjaman->tanggal_batal || $peminjaman->tanggal_setujui || $peminjam->tanggal_kembali)
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Histori</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        @if($peminjaman->tanggal_pinjam)
                        <dt class="col-sm-3">Tanggal Pinjam : </dt>
                        <dd class="col-sm-2">{{ $peminjaman->tanggal_pinjam->locale('id')->translatedFormat('d F Y') }}</dd>
                        <dt class="col-sm-2">Catatan : </dt>
                        <dd class="col-sm-3">{{ $peminjaman->catatan_pinjam }}</dd>
                        @endif

    
                         @if($peminjaman->tanggal_batal)
                        <dt class="col-sm-3">Tanggal Dibatalkan : </dt>
                        <dd class="col-sm-2">{{ $peminjaman->tanggal_batal->locale('id')->translatedFormat('d F Y') }}</dd>
                        <dt class="col-sm-2">Catatan : </dt>
                        <dd class="col-sm-3">{{ $peminjaman->catatan_batal }}</dd>
                        @endif

                         @if($peminjaman->tanggal_setujui)
                        <dt class="col-sm-3">Tanggal Disetujui : </dt>
                        <dd class="col-sm-2">{{ $peminjaman->tanggal_setujui->locale('id')->translatedFormat('d F Y') }}</dd>
                        <dt class="col-sm-2">Catatan : </dt>
                        <dd class="col-sm-3">{{ $peminjaman->catatan_setujui }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
            @endif
        </div>

        <!-- Card Footer with Action Buttons -->
        <div class="card-footer bg-light d-flex justify-content-between">
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary text-white">
                Kembali
            </a>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    dt {
        font-weight: 500;
        color: #6c757d;
    }
    .card-header {
        border-bottom: none;
    }
</style>
@endpush
