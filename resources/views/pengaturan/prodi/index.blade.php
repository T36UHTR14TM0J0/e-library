@extends('layouts.app')
@section('title', 'Data Prodi')

@section('content')
<div class="container-fluid px-4">
    <div class="row my-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-filter-square me-2"></i> Filter Data Prodi</h5>
                        <a href="{{ route('prodi.create') }}" class="btn btn-sm btn-light">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Prodi
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('prodi.index') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="kode" class="form-label">Kode Prodi</label>
                                <input type="text" name="kode" id="kode" class="form-control form-control-sm" 
                                       placeholder="Masukkan kode prodi" value="{{ request('kode') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Prodi</label>
                                <input type="text" name="nama" id="nama" class="form-control form-control-sm" 
                                       placeholder="Masukkan nama prodi" value="{{ request('nama') }}">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            @if(request()->has('kode') || request()->has('nama'))
                            <a href="{{ route('prodi.index') }}" class="btn btn-sm btn-outline-secondary me-2">
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
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i> Daftar Program Studi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" style="font-size: 0.875rem">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center bg-primary text-white" width="5%">No</th>
                                    <th class="text-center bg-primary text-white" width="10%">Kode</th>
                                    <th class="text-center bg-primary text-white">Nama Prodi</th>
                                    <th class="text-center bg-primary text-white">Deskripsi</th>
                                    <th class="text-center bg-primary text-white" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($prodi as $row)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($prodi->currentPage() - 1) * $prodi->perPage() }}</td>
                                    <td class="text-center">{{ $row->kode }}</td>
                                    <td>{{ $row->nama }}</td>
                                    <td>{{ $row->deskripsi ?: '-' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('prodi.edit', $row->id) }}" class="btn btn-sm btn-warning text-white" 
                                               data-bs-toggle="tooltip" title="Edit">
                                                Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm text-white" onclick="confirmDelete('{{ $row->id }}')"
                                               data-bs-toggle="tooltip" title="Hapus">
                                                Hapus
                                            </button>
                                            <form id="delete-form-{{ $row->id }}" action="{{ route('prodi.destroy', $row->id) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="bi bi-database-x me-2"></i> Tidak ada data prodi
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Menampilkan <strong>{{ $prodi->firstItem() }}</strong> sampai <strong>{{ $prodi->lastItem() }}</strong> 
                            dari <strong>{{ $prodi->total() }}</strong> entri
                        </div>
                        <div>
                            {{ $prodi->links() }}
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