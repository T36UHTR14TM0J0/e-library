@extends('layouts.app')
@section('title', 'Log Aktivitas Sistem')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-funnel me-2"></i> Filter Log Aktivitas</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('logs.index') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="search" class="form-label">Cari Aktivitas</label>
                                <input type="text" name="search" id="search" class="form-control form-control-sm" 
                                       placeholder="Masukkan pencarian..." value="{{ request('search') }}">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            @if(request()->has('search'))
                            <a href="{{ route('logs.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </a>
                            @endif
                            <button type="submit" class="btn btn-sm btn-dark">
                                <i class="bi bi-funnel me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-2 shadow-sm">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i> Daftar Log Aktivitas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white bg-primary text-center" style="width: 15%">Waktu</th>
                                    <th class="text-white bg-primary text-center" style="width: 15%">User</th>
                                    <th class="text-white bg-primary text-center">Deskripsi</th>
                                    <th class="text-white bg-primary text-center" style="width: 12%">IP Address</th>
                                    <th class="text-white bg-primary text-center" style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                <tr>
                                    <td class="text-center">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">{{ $log->user?->username ?? 'System' }}</td>
                                    <td>{{ $log->deskripsi }}</td>
                                    <td class="text-center">{{ $log->ip_address }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('logs.show', $log) }}" 
                                           class="btn btn-sm btn-info text-white"
                                           data-bs-toggle="tooltip" title="Detail">
                                            detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada log aktivitas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $logs->firstItem() }} sampai {{ $logs->lastItem() }} 
                            dari {{ $logs->total() }} entri
                        </div>
                        <div>
                            {{ $logs->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Enable tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
@endsection