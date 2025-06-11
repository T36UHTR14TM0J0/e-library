@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
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
        <div class="col-md-4">
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
        
        <div class="col-md-4">
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
        
        {{-- <div class="col-md-4">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Buku Dipinjam</h5>
                            <h2 class="mb-0">{{ $borrowedBooks }}</h2>
                        </div>
                        <i class="fas fa-exchange-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    
    <!-- Overdue Books Section -->
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Buku dengan Tenggat Pinjam</h5>
        </div>
        <div class="card-body">
            {{-- @if($overdueBooks->isEmpty())
                <div class="alert alert-info">Tidak ada buku yang melewati tenggat pinjam</div>
            @else --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Judul Buku</th>
                                <th>Peminjam</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tenggat Kembali</th>
                                <th>Terlambat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($overdueBooks as $book)
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->user->name }}</td>
                                <td>{{ $book->borrow_date->format('d M Y') }}</td>
                                <td>{{ $book->due_date->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-danger">
                                        {{ now()->diffInDays($book->due_date) }} hari
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-envelope"></i> Ingatkan
                                    </button>
                                </td>
                            </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            {{-- @endif --}}
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