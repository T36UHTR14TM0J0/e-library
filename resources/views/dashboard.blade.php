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
                            <h5 class="card-title text-white">Total User / Pengguna</h5>
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
    
    <!-- User Statistics Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Statistik Status Pengguna</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:250px;">
                        <canvas id="userStatusChart"></canvas>
                    </div>
                    <div class="mt-3 text-center">
                        <span class="badge bg-success me-2"> Aktif: {{ $activeUsers }}</span>
                        <span class="badge bg-danger"> Tidak Aktif: {{ $inactiveUsers }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- You can add another chart or content here -->
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User Status Donut Chart
        const ctx = document.getElementById('userStatusChart').getContext('2d');
        const userStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Tidak Aktif'],
                datasets: [{
                    data: [{{ $activeUsers }}, {{ $inactiveUsers }}],
                    backgroundColor: [
                        '#28a745', // Green for active
                        '#dc3545'  // Red for inactive
                    ],
                    borderWidth: 0,
                    cutout: '70%' // Makes it a donut instead of pie
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false, // We'll use custom legend
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((context.raw / total) * 100);
                                label += context.raw + ' (' + percentage + '%)';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

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