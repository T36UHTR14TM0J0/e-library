@extends('layouts.app')
@section('title', 'Katalog Buku')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 mb-5">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filter">
                <i class="icon-search me-1"></i> Filter
            </button>
            @if(request()->hasAny(['judul', 'penulis', 'kategori_id', 'prodi_id', 'izin_unduh']))
            <a href="{{ route('KatalogBuku.index') }}" class="btn btn-sm btn-outline-danger">
                <i class="icon-close me-1"></i> Reset Filter
            </a>
            @endif
        </div>
                
        <div class="col-md-12">
            @if($bukus->isEmpty())
            <div class="text-center py-4">
                <img src="{{ asset('assets/images/default-cover.png') }}" alt="No data" style="height: 150px;">
                <h5 class="mt-3">Tidak ada Buku tersedia</h5>
            </div>
            @else
            <div class="row g-4 px-3">
                @foreach ($bukus as $buku)
                <div class="col-md-4">
                    <div class="card card-hover h-100">
                        <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                            <div class="d-block blur-shadow-image">
                                <img src="{{ asset('storage/' . $buku->gambar_sampul) ?? asset('assets/images/default-cover.png') }}" 
                                    alt="{{ $buku->judul }}" 
                                    class="img-fluid border-radius-lg" 
                                    style="height: 200px; width: 100%; object-fit: cover;">
                            </div>
                            <div class="colored-shadow" 
                                style="background-image: url('{{ $buku->gambar_sampul ?? asset('assets/images/default-cover.png') }}');"></div>
                        </div>
                        <div class="card-body pt-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-gradient-danger">
                                    {{ $buku->prodi->nama ?? '-' }}
                                </span>
                                <span class="badge bg-info">
                                    <td>Tersedia : {{ $buku->tersedia() }}</td>
                                </span>
                            </div>
                            <h5 class="font-weight-normal">
                                <a href="#" class="text-dark">{{ Str::limit($buku->judul, 50) }}</a>
                            </h5>
                            <p class="mb-0 text-sm">
                                {{ $buku->penulis }}
                            </p>
                            {{-- <hr class="horizontal dark my-2"> --}}
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-secondary">
                                    {{ $buku->kategori->nama ?? '-' }}
                                </span>
                                <span class="badge bg-success">
                                    {{ $buku->created_at ?? '-' }}
                                </span>
                            </div>
                            <hr class="horizontal dark my-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="{{ route('KatalogBuku.show', $buku->id) }}" class="btn btn-sm btn-outline-info">
                                        Detail
                                    </a>
                                </div>
                                <small class="text-muted">
                                    <a href="#" class="btn btn-sm btn-outline-success">
                                        Pinjam
                                    </a>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center px-4 mt-4">
                <div class="text-muted">
                    Menampilkan {{ $bukus->firstItem() }} sampai {{ $bukus->lastItem() }} dari {{ $bukus->total() }} Buku
                </div>
                <div>
                    {{ $bukus->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filter" tabindex="-1" aria-labelledby="filterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="GET" action="{{ route('KatalogBuku.index') }}">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="filterLabel">
                        <i class="icon-search me-2"></i>Filter Buku
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Buku</label>
                        <input type="text" name="judul" id="judul" class="form-control" 
                            placeholder="Cari berdasarkan judul" value="{{ request('judul') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="penulis" class="form-label">Penulis</label>
                        <input type="text" name="penulis" id="penulis" class="form-control" 
                            placeholder="Cari berdasarkan penulis" value="{{ request('penulis') }}">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="prodi_id" class="form-label">Program Studi</label>
                            <select name="prodi_id" id="prodi_id" class="form-select">
                                <option value="">Semua Prodi</option>
                                @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary text-white">
                        Reset
                    </button>
                    <button type="submit" class="btn bg-primary text-white">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .modal-fullscreen {
        min-width: 100%;
    }
    .modal-xl {
        max-width: 95%;
    }
    .modal-body iframe {
        border: none;
    }
</style>
@endpush
@endsection