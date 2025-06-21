@extends('layouts.app')
@section('title', 'Jam Layanan')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-clock me-2"></i> Jam Layanan</h5>
                        <a href="{{ route('jam_layanan.create') }}" class="btn btn-sm btn-light">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Jam Layanan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('jam_layanan.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="hari" class="form-label">Hari</label>
                                <select name="hari" id="hari" class="form-select form-select-sm">
                                    <option value="">Semua Hari</option>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                        <option value="{{ $day }}" {{ request('hari') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            @if(request()->has('hari'))
                            <a href="{{ route('jam_layanan.index') }}" class="btn btn-sm btn-outline-secondary me-2">
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
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i> Daftar Jam Layanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white bg-primary text-center" style="width: 5%">No</th>
                                    <th class="text-white bg-primary text-center" style="width: 15%">Hari</th>
                                    <th class="text-white bg-primary text-center" style="width: 15%">Waktu Buka</th>
                                    <th class="text-white bg-primary text-center" style="width: 15%">Waktu Tutup</th>
                                    <th class="text-white bg-primary text-center">Catatan</th>
                                    <th class="text-white bg-primary text-center" style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no= ($jamLayanans->currentPage() - 1) * $jamLayanans->perPage() + 1; ?>
                                @forelse ($jamLayanans as $jam)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-center">{{ $jam->hari }}</td>
                                    <td class="text-center">{{ date('H:i', strtotime($jam->waktu_buka)) }}</td>
                                    <td class="text-center">{{ date('H:i', strtotime($jam->waktu_tutup)) }}</td>
                                    <td>{{ $jam->catatan ? Str::limit($jam->catatan, 50) : '-' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('jam_layanan.edit', $jam->id) }}" 
                                               class="btn btn-sm btn-warning text-white" 
                                               data-bs-toggle="tooltip" title="Edit">
                                                Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm text-white" 
                                                    onclick="confirmDelete('{{ $jam->id }}')"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                Hapus
                                            </button>
                                            <form id="delete-form-{{ $jam->id }}" 
                                                  action="{{ route('jam_layanan.destroy', $jam->id) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $jamLayanans->firstItem() }} sampai {{ $jamLayanans->lastItem() }} 
                            dari {{ $jamLayanans->total() }} entri
                        </div>
                        <div>
                            {{ $jamLayanans->links('vendor.pagination.bootstrap-5') }}
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