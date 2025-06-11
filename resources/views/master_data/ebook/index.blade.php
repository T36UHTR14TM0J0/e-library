@extends('layouts.app')
@section('title', 'Data Ebook')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-filter-square me-2"></i> Filter Data Ebook</h5>
                        <a href="{{ route('ebook.create') }}" class="btn btn-sm btn-light">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Ebook
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('ebook.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="judul" class="form-label">Judul Ebook</label>
                                <input type="text" name="judul" id="judul" class="form-control form-control-sm" 
                                       placeholder="Masukkan judul ebook" value="{{ request('judul') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="penerbit" class="form-label">Penerbit</label>
                                <select name="kategori_id" id="kategori_id" class="form-select form-select-sm">
                                    <option value="">Semua Penerbit</option>
                                    @foreach($penerbits as $penerbit)
                                        <option value="{{ $penerbit->id }}" {{ request('penerbit_id') == $penerbit->id ? 'selected' : '' }}>
                                            {{ $penerbit->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="kategori_id" class="form-label">Kategori</label>
                                <select name="kategori_id" id="kategori_id" class="form-select form-select-sm">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="prodi_id" class="form-label">Program Studi</label>
                                <select name="prodi_id" id="prodi_id" class="form-select form-select-sm">
                                    <option value="">Semua Prodi</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                            {{ $prodi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="izin_unduh" class="form-label">Izin Unduh</label>
                                <select name="izin_unduh" id="izin_unduh" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ request('izin_unduh') == '1' ? 'selected' : '' }}>Diizinkan</option>
                                    <option value="0" {{ request('izin_unduh') == '0' ? 'selected' : '' }}>Tidak Diizinkan</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            @if(request()->has('judul') || request()->has('penulis') || request()->has('kategori_id') || request()->has('prodi_id') || request()->has('izin_unduh'))
                            <a href="{{ route('ebook.index') }}" class="btn btn-sm btn-outline-secondary me-2">
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
            
            <div class="card mt-3 shadow-sm">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <h5 class="mb-0"><i class="bi bi-book me-2"></i> Daftar Ebook</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white text-center bg-primary" style="width: 5%">No</th>
                                    <th class="text-white text-center bg-primary" style="width: 20%">Judul</th>
                                    <th class="text-white text-center bg-primary" style="width: 15%">Penerbit</th>
                                    <th class="text-white text-center bg-primary" style="width: 15%">Kategori</th>
                                    <th class="text-white text-center bg-primary" style="width: 15%">Prodi</th>
                                    <th class="text-white text-center bg-primary" style="width: 15%">Uploader</th>
                                    <th class="text-white text-center bg-primary" style="width: 10%">Izin Unduh</th>
                                    <th class="text-white text-center bg-primary" style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no= 1;?>
                                @forelse ($ebooks as $ebook)
                                <tr>
                                    <td class="text-center"><?= $no++;?></td>
                                    <td>{{ $ebook->judul }}</td>
                                    <td>{{ $ebook->penerbit->nama }}</td>
                                    <td>{{ $ebook->kategori->nama ?? '-' }}</td>
                                    <td>{{ $ebook->prodi->nama ?? '-' }}</td>
                                    <td>{{ $ebook->pengunggah->nama_lengkap ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($ebook->izin_unduh)
                                            <span class="badge bg-success">Ya</span>
                                        @else
                                            <span class="badge bg-danger">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('ebook.show',$ebook->id) }}" class="btn btn-sm btn-info text-white" 
                                               data-bs-toggle="tooltip" title="Lihat">
                                                Detail
                                            </a>
                                            <a href="{{ route('ebook.edit',$ebook->id) }}" class="btn btn-sm btn-warning text-white" 
                                               data-bs-toggle="tooltip" title="Edit">
                                                Edit
                                            </a>
                                            <button class="btn btn-sm btn-danger text-white" title="Hapus" 
                                                    onclick="confirmDelete('{{ $ebook->id }}')" data-bs-toggle="tooltip">
                                                Hapus
                                            </button>
                                            <form id="delete-form-{{ $ebook->id }}" action="{{ route('ebook.destroy', $ebook->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $ebooks->firstItem() }} sampai {{ $ebooks->lastItem() }} dari {{ $ebooks->total() }} entri
                        </div>
                        <div>
                            {{ $ebooks->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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