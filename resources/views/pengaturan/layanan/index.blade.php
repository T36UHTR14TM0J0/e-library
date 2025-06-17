@extends('layouts.app')
@section('title', 'Data Layanan')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-filter-square me-2"></i> Filter Data Layanan</h5>
                        <a href="{{ route('layanan.create') }}" class="btn btn-sm btn-light">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Layanan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('layanan.index') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Layanan</label>
                                <input type="text" name="nama" id="nama" class="form-control form-control-sm" 
                                        placeholder="Masukkan nama layanan" value="{{ request('nama') }}">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            @if(request()->has('nama'))
                            <a href="{{ route('layanan.index') }}" class="btn btn-sm btn-outline-secondary me-2">
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
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i> Daftar Layanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white bg-primary text-center" style="width: 5%">No</th>
                                    <th class="text-white bg-primary text-center" style="width: 20%">Nama</th>
                                    <th class="text-white bg-primary text-center" style="width: 15%">Icon</th>
                                    <th class="text-white bg-primary text-center">Deskripsi</th>
                                    <th class="text-white bg-primary text-center" style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no= ($layanans->currentPage() - 1) * $layanans->perPage() + 1; ?>
                                @forelse ($layanans as $layanan)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $layanan->nama }}</td>
                                    <td class="text-center">
                                         {{ $layanan->icon }}
                                    </td>
                                    <td>{{ Str::limit($layanan->deskripsi, 50) }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('layanan.edit', $layanan->id) }}" 
                                               class="btn btn-sm btn-warning text-white" 
                                               data-bs-toggle="tooltip" title="Edit">
                                                Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm text-white" 
                                                    onclick="confirmDelete('{{ $layanan->id }}')"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                Hapus
                                            </button>
                                            <form id="delete-form-{{ $layanan->id }}" 
                                                  action="{{ route('layanan.destroy', $layanan->id) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $layanans->firstItem() }} sampai {{ $layanans->lastItem() }} 
                            dari {{ $layanans->total() }} entri
                        </div>
                        <div>
                            {{ $layanans->links() }}
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