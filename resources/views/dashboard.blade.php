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

    <div class="accordion accordion-flush" id="accordionFlushExample">
        {{-- STATISTIK PENGGUNA --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed bg-info text-white" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    Statistik Status Pengguna
                </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
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
                </div>
            </div>
        </div>

        {{-- BUKU SERING DIPINJAM --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingTwo">
            <button class="accordion-button collapsed bg-success text-white" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                Buku yang Sering Dipinjam
            </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Buku yang Sering Dipinjam</h5>
                        </div>
                        <div class="card-body">
                            @if($TopBukuDipinjam->isEmpty())
                                <div class="alert alert-info">Belum ada data peminjaman buku</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-white bg-success" width="5%">No</th>
                                                <th class="text-white bg-success">Judul Buku</th>
                                                <th class="text-white bg-success">Penulis</th>
                                                <th class="text-white bg-success">Penerbit</th>
                                                <th class="text-white bg-success">Tahun Terbit</th>
                                                <th class="text-white bg-success">Jumlah Dipinjam</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($TopBukuDipinjam as $index => $book)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $book->buku->judul }}</td>
                                                <td>{{ $book->buku->penulis }}</td>
                                                <td>{{ $book->buku->penerbit->nama }}</td>
                                                <td>{{ $book->buku->tahun_terbit }}</td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        {{ $book->total_peminjaman }} kali
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingThree">
            <button class="accordion-button collapsed bg-danger text-white" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                Buku dengan Tenggat Pinjam
            </button>
            </h2>
            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <div class="card shadow-sm">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">Buku dengan Tenggat Pinjam</h5>
                        </div>
                        <div class="card-body">
                            @if($TenggatList->isEmpty())
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
                                            @foreach($TenggatList as $index => $book)
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
            </div>
        </div>

        {{-- DATA PEMINJAMAN FILTER --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingFour">
                <button class="accordion-button collapsed bg-secondary text-white" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                    Data Peminjaman perhari/perminggu/perbulan
                </button>
            </h2>
            <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="filterType" class="form-label">Jenis Filter</label>
                            <select class="form-select" id="filterType">
                                <option value="daily">Harian</option>
                                <option value="weekly">Mingguan</option>
                                <option value="monthly">Bulanan</option>
                                <option value="yearly">Tahunan</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="dateFilterContainer">
                            <label for="filterDate" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="filterDate" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4 d-none" id="weekFilterContainer">
                            <label for="filterWeek" class="form-label">Minggu ke-</label>
                            <input type="week" class="form-control" id="filterWeek" value="{{ date('Y-\WW') }}">
                        </div>
                        <div class="col-md-4 d-none" id="monthFilterContainer">
                            <label for="filterMonth" class="form-label">Bulan</label>
                            <input type="month" class="form-control" id="filterMonth" value="{{ date('Y-m') }}">
                        </div>
                        <div class="col-md-4 d-none" id="yearFilterContainer">
                            <label for="filterYear" class="form-label">Tahun</label>
                            <select class="form-select" id="filterYear">
                                @for($year = date('Y') - 2; $year <= date('Y') + 2; $year++)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary mb-3" id="filterButton">
                        Filter Data
                    </button>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peminjam</th>
                                    <th>Judul Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                    <th>Denda</th>
                                </tr>
                            </thead>
                            <tbody id="loanDataBody">
                                <tr>
                                    <td colspan="7" class="text-center">Pilih filter terlebih dahulu</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Controls (Sederhana) -->
                    <div class="d-flex justify-content-between mt-3">
                        <button id="prevPage" class="btn btn-outline-primary" disabled>
                            &laquo; Sebelumnya
                        </button>
                        <span id="pageInfo" class="align-self-center">Halaman 1</span>
                        <button id="nextPage" class="btn btn-outline-primary" disabled>
                            Selanjutnya &raquo;
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tambahkan di dalam accordion setelah accordion-item terakhir -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingFive">
                <button class="accordion-button collapsed bg-primary text-white" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                    Statistik Peminjaman Harian
                </button>
            </h2>
            <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="chartStartDate" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="chartStartDate" value="{{ $chartData['start_date'] }}">
                        </div>
                        <div class="col-md-4">
                            <label for="chartEndDate" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="chartEndDate" value="{{ $chartData['end_date'] }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary" id="updateChartBtn">
                                <i class="mdi mdi-refresh"></i> Perbarui Grafik
                            </button>
                        </div>
                    </div>
                    
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Rata-Rata Peminjaman Per Hari</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="loanChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tambahkan setelah accordion Statistik Peminjaman Harian -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingSix">
                <button class="accordion-button collapsed bg-info text-white" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                    Aktivitas Pengguna
                </button>
            </h2>
            <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <div class="row">
                        <!-- Kolom Top 10 Pengguna Sering Meminjam -->
                        <div class="col-md-6">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Top 10 Pengguna Sering Meminjam</h5>
                                </div>
                                <div class="card-body">
                                    @if($topPeminjam->isEmpty())
                                        <div class="alert alert-info">Belum ada data peminjaman</div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th>Nama Pengguna</th>
                                                        <th>Total Pinjam</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($topPeminjam as $index => $user)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $user->user->nama_lengkap }}</td>
                                                        <td>
                                                            <span class="badge bg-primary">
                                                                {{ $user->total_peminjaman }} kali
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Top 10 Pengguna Sering Membaca -->
                        <div class="col-md-6">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Top 10 Pengguna Sering Membaca Ebook</h5>
                                </div>
                                <div class="card-body">
                                    @if($topPembaca->isEmpty())
                                        <div class="alert alert-info">Belum ada data membaca ebook</div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th>Nama Pengguna</th>
                                                        <th>Total Baca</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($topPembaca as $index => $user)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $user->user->nama_lengkap }}</td>
                                                        <td>
                                                            <span class="badge bg-success">
                                                                {{ $user->total_bacaan }} kali
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

        const loanCtx = document.getElementById('loanChart').getContext('2d');
        const loanChart = new Chart(loanCtx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: @json($chartData['data']),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Peminjaman'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Peminjaman: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
        
        // Fungsi untuk update chart
        document.getElementById('updateChartBtn').addEventListener('click', function() {
            const startDate = document.getElementById('chartStartDate').value;
            const endDate = document.getElementById('chartEndDate').value;
            
            fetch(`/dashboard/loan-chart-data?start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loanChart.data.labels = data.data.labels;
                        loanChart.data.datasets[0].data = data.data.data;
                        loanChart.update();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data chart');
                });
        });

        // Filter functionality
        const filterType = document.getElementById('filterType');
        const filterButton = document.getElementById('filterButton');
        
        // Initialize filter containers
        const filterContainers = {
            daily: document.getElementById('dateFilterContainer'),
            weekly: document.getElementById('weekFilterContainer'),
            monthly: document.getElementById('monthFilterContainer'),
            yearly: document.getElementById('yearFilterContainer')
        };
        
        // Hide all filter containers except daily
        Object.values(filterContainers).forEach(container => {
            if (container.id !== 'dateFilterContainer') {
                container.classList.add('d-none');
            }
        });
        
        // Handle filter type change
        filterType.addEventListener('change', function() {
            const selectedFilter = this.value;
            
            // Hide all containers first
            Object.values(filterContainers).forEach(container => {
                container.classList.add('d-none');
            });
            
            // Show selected container
            filterContainers[selectedFilter].classList.remove('d-none');
        });
        
        let currentPage = 1;
        let lastPage = 1;

        filterButton.addEventListener('click', function() {
            currentPage = 1; // Reset ke halaman pertama saat filter baru
            updatePaginationControls();
            fetchLoanData();
        });

        // Fungsi untuk mengambil data peminjaman
        function fetchLoanData() {
            const selectedFilter = filterType.value;
            let filterValue;
            
            switch(selectedFilter) {
                case 'daily':
                    filterValue = document.getElementById('filterDate').value;
                    break;
                case 'weekly':
                    filterValue = document.getElementById('filterWeek').value;
                    break;
                case 'monthly':
                    filterValue = document.getElementById('filterMonth').value;
                    break;
                case 'yearly':
                    filterValue = document.getElementById('filterYear').value;
                    break;
            }
            
            if (!filterValue) {
                alert('Silakan pilih nilai filter terlebih dahulu');
                return;
            }
            
            fetch(`/dasboard/filterpeminjaman?filter_type=${selectedFilter}&filter_value=${filterValue}&page=${currentPage}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const tableBody = document.getElementById('loanDataBody');
                    tableBody.innerHTML = '';
                    
                    if (data.data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Tidak ada data ditemukan</td></tr>';
                        return;
                    }
                    
                    data.data.forEach((item, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${((currentPage - 1) * 10) + index + 1}</td>
                            <td>${item.user ? item.user.nama_lengkap : 'N/A'}</td>
                            <td>${item.buku ? item.buku.judul : 'N/A'}</td>
                            <td>${new Date(item.tanggal_pinjam).toLocaleDateString('id-ID')}</td>
                            <td>${item.tanggal_kembali ? new Date(item.tanggal_kembali).toLocaleDateString('id-ID') : '-'}</td>
                            <td>
                                <span class="badge ${getStatusBadgeClass(item.status)}">
                                    ${item.status}
                                </span>
                            </td>
                            <td>${item.denda_formatted}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                    
                    // Update pagination info
                    lastPage = data.meta.last_page;
                    updatePaginationControls();
                })
                .catch(error => {
                    console.error('Error:', error);
                    const tableBody = document.getElementById('loanDataBody');
                    tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Terjadi kesalahan saat memuat data</td></tr>';
                });
        }

        // Fungsi untuk update kontrol pagination
        function updatePaginationControls() {
            document.getElementById('pageInfo').textContent = `Halaman ${currentPage}`;
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === lastPage;
        }

        // Event listeners untuk tombol pagination
        document.getElementById('prevPage').addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                fetchLoanData();
                window.scrollTo({ top: document.getElementById('loanDataBody').offsetTop, behavior: 'smooth' });
            }
        });

        document.getElementById('nextPage').addEventListener('click', function() {
            if (currentPage < lastPage) {
                currentPage++;
                fetchLoanData();
                window.scrollTo({ top: document.getElementById('loanDataBody').offsetTop, behavior: 'smooth' });
            }
        });

        
        // Helper function for status badge classes
        function getStatusBadgeClass(status) {
            switch(status) {
                case 'dipinjam': return 'bg-primary';
                case 'dikembalikan': return 'bg-success';
                case 'menunggu': return 'bg-warning text-dark';
                case 'dibatalkan': return 'bg-danger';
                default: return 'bg-secondary';
            }
        }
    });
</script>
@endpush

<style>
    .card {
        border-radius: 10px;
        transition: transform 0.2s;
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem 0 rgba(58, 59, 69, 0.2);
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    .accordion-button:not(.collapsed) {
        color: white;
        background-color: var(--bs-accordion-active-bg);
    }
    .table th {
        white-space: nowrap;
    }

    /* Tambahkan di bagian style */
    .btn-outline-primary {
        min-width: 120px;
    }
    #pageInfo {
        font-weight: bold;
    }
</style>
@endsection