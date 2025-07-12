@extends('layouts.app')
@section('title', 'Data Informasi')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-building me-2"></i> Filter Data Informasi</h5>
                        <a href="{{ route('informasi.create') }}" class="btn btn-sm btn-light">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Informasi
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('informasi.index') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="judul" class="form-label">Judul Informasi</label>
                                <input type="text" name="judul" id="judul" class="form-control form-control-sm" 
                                        placeholder="Masukkan judul fasilitas" value="{{ request('judul') }}">
                            </div>
                        
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            @if(request()->hasAny(['judul']))
                            <a href="{{ route('informasi.index') }}" class="btn btn-sm btn-outline-secondary me-2 text-white">
                               Reset
                            </a>
                            @endif
                            <button type="submit" class="btn btn-sm btn-dark">
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-2 shadow-sm">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <h5 class="mb-0"><i class="bi bi-list-check me-2"></i> Daftar Informasi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white bg-primary text-center" style="width: 5%">No</th>
                                    <th class="text-white bg-primary text-center" style="width: 20%">Judul</th>
                                    <th class="text-white bg-primary text-center" style="width: 15%">Kapasitas</th>
                                    <th class="text-white bg-primary text-center" style="width: 15%">Jam Operasional</th>
                                    <th class="text-white bg-primary text-center" style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no= ($informasis->currentPage() - 1) * $informasis->perPage() + 1; ?>
                                @forelse ($informasis as $informasi)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>
                                        <strong>{{ $informasi->judul }}</strong>
                                    </td>
                                    <td class="text-center">
                                        {{ $informasi->min_kapasitas }} - {{ $informasi->maks_kapasitas }} orang
                                    </td>
                                    <td class="text-center">
                                        {{ $informasi->waktu_buka }} - {{ $informasi->waktu_tutup }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('informasi.edit', $informasi->id) }}" 
                                               class="btn btn-sm btn-warning text-white" 
                                               data-bs-toggle="tooltip" title="Edit">
                                                Edit
                                            </a>
                                            <a href="{{ route('informasi.show', $informasi->id) }}" 
                                               class="btn btn-sm btn-info text-white" 
                                               data-bs-toggle="tooltip" title="Detail">
                                                Detail
                                            </a>
                                            <button class="btn btn-danger btn-sm text-white" 
                                                    onclick="confirmDelete('{{ $informasi->id }}')"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                Hapus
                                            </button>
                                            <form id="delete-form-{{ $informasi->id }}" 
                                                  action="{{ route('informasi.destroy', $informasi->id) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data fasilitas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $informasis->firstItem() }} sampai {{ $informasis->lastItem() }} 
                            dari {{ $informasis->total() }} entri
                        </div>
                        <div>
                            {{ $informasis->links('vendor.pagination.bootstrap-5') }}
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