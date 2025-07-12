@extends('layouts.app')
@section('title', 'Data Galeri Perpustakaan')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-filter-square me-2"></i> Filter Data Galeri Perpustakaan</h5>
                        <a href="{{ route('galeri.create') }}" class="btn btn-sm btn-light">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Galeri
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('galeri.index') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="judul" class="form-label">Judul Galeri</label>
                                <input type="text" name="judul" id="judul" class="form-control form-control-sm" 
                                        placeholder="Masukkan judul galeri" value="{{ request('judul') }}">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            @if(request()->has('judul'))
                            <a href="{{ route('galeri.index') }}" class="btn btn-sm btn-outline-secondary me-2">
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
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i> Daftar Galeri Perpustakaan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white bg-primary text-center" style="width: 5%">No</th>
                                    <th class="text-white bg-primary text-center" style="width: 10%">Urut</th>
                                    <th class="text-white bg-primary text-center" style="width: 15%">Foto</th>
                                    <th class="text-white bg-primary text-center" style="width: 20%">Judul</th>
                                    <th class="text-white bg-primary text-center">Deskripsi</th>
                                    <th class="text-white bg-primary text-center" style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no= ($galeris->currentPage() - 1) * $galeris->perPage() + 1; ?>
                                @forelse ($galeris as $galeri)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-center">{{ $galeri->urut }}</td>
                                    <td class="text-center">
                                        @if($galeri->foto)
                                            <img src="{{ asset('storage/' . $galeri->foto) }}" alt="{{ $galeri->judul }}" class="img-thumbnail" style="max-height: 200px;">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $galeri->judul }}</td>
                                    <td>{{ Str::limit($galeri->deskripsi, 50) }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('galeri.edit', $galeri->id) }}" 
                                               class="btn btn-sm btn-warning text-white" 
                                               data-bs-toggle="tooltip" title="Edit">
                                                Edit
                                            </a>
                                            <a href="{{ route('galeri.show', $galeri->id) }}" 
                                               class="btn btn-sm btn-info text-white" 
                                               data-bs-toggle="tooltip" title="Detail">
                                                Detail
                                            </a>
                                            <button class="btn btn-danger btn-sm text-white" 
                                                    onclick="confirmDelete('{{ $galeri->id }}')"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                Hapus
                                            </button>
                                            <form id="delete-form-{{ $galeri->id }}" 
                                                  action="{{ route('galeri.destroy', $galeri->id) }}" 
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
                            Menampilkan {{ $galeris->firstItem() }} sampai {{ $galeris->lastItem() }} 
                            dari {{ $galeris->total() }} entri
                        </div>
                        <div>
                            {{ $galeris->links('vendor.pagination.bootstrap-5') }}
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