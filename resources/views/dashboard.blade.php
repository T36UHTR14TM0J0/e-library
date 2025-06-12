@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mt-2">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white">Jumlah Buku</h5>
                            <h2 class="mb-0">{{ $totalBooks }}</h2>
                        </div>
                        <i class="icon-book fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-2">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white">Jumlah EBook</h5>
                            <h2 class="mb-0">{{ $totalEbook }}</h2>
                        </div>
                        <i class="icon-book fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mt-2">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white">Jumlah User</h5>
                            <h2 class="mb-0">{{ $totalUsers }}</h2>
                        </div>
                        <i class="mdi mdi-account-multiple fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mt-2">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-white">
                            <h5 class="card-title text-white">Buku Dipinjam</h5>
                            <h2 class="mb-0">{{ $peminjamanBuku }}</h2>
                        </div>
                        <i class="mdi mdi-book-multiple fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Overdue Books Section -->
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Buku dengan Tenggat Pinjam</h5>
        </div>
        <div class="card-body">
            @if($overdueBooks->isEmpty())
                <div class="alert alert-info">Tidak ada buku yang melewati tenggat pinjam</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-white bg-info" width="5%">No</th>
                                <th class="text-white bg-info">Judul Buku</th>
                                <th class="text-white bg-info">Peminjam</th>
                                <th class="text-white bg-info">Tanggal Pinjam</th>
                                <th class="text-white bg-info">Tenggat Kembali</th>
                                <th class="text-white bg-info">Terlambat</th>
                                <th class="text-white bg-info">Denda</th>
                                {{-- <th width="10%">Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overdueBooks as $index => $book)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $book->buku->judul }}</td>
                                <td>{{ $book->user->nama_lengkap }}</td>
                                <td>{{ $book->tanggal_pinjam->locale('id')->translatedFormat('d F Y') }}</td>
                                <td>{{ $book->tanggal_jatuh_tempo->locale('id')->translatedFormat('d F Y') }}</td>
                                <td>
                                    <span class="badge bg-danger">
                                        {{ now()->diffInDays($book->tanggal_jatuh_tempo) }} hari
                                    </span>
                                </td>
                                <td>Rp {{ number_format($book->hitungDenda(), 0, ',', '.') }}</td>
                                {{-- <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary" title="Kirim Pengingat">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                        <a href="{{ route('peminjaman.show', $book->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 10px;
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .card-title {
        font-size: 1rem;
        font-weight: 500;
    }
</style>
@endsection