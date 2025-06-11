@extends('layouts.app')
@section('title', 'Data Buku')
@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('buku.create') }}" class="btn btn-sm btn-inverse-primary">
                <i class="icon-plus"></i> Tambah Data
            </a>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-inverse-dark btn-sm" data-bs-toggle="modal" data-bs-target="#filter">
              <i class="icon-search"></i> Filter
            </button>
            @if(request()->has('nama') || request()->has('penulis') || request()->has('kategori_id') || request()->has('prodi_id'))
            <a href="{{ route('buku.index') }}" class="btn btn-sm btn-inverse-danger">
                <i class="icon-close"></i> Batal Filter
            </a>
            @endif
        </div>

        <!-- Books Table -->
        <div class="card mt-2">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="bg-primary">
                            <tr>
                                <th class="text-white text-center bg-primary">No</th>
                                <th class="text-white text-center bg-primary">ISBN</th>
                                <th class="text-white text-center bg-primary">Judul</th>
                                <th class="text-white text-center bg-primary">Penulis</th>
                                <th class="text-white text-center bg-primary">Kategori</th>
                                <th class="text-white text-center bg-primary">Prodi</th>
                                <th class="text-white text-center bg-primary">Jumlah</th>
                                <th class="text-white text-center bg-primary">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no= 1;?>
                            @forelse ($bukus as $buku)
                            <tr>
                                <td class="text-center"><?= $no++;?></td>
                                <td>{{ $buku->isbn ?? '-' }}</td>
                                <td>{{ $buku->judul }}</td>
                                <td>{{ $buku->penulis }}</td>
                                <td>{{ $buku->kategori->nama ?? '-' }}</td>
                                <td>{{ $buku->prodi->nama ?? '-' }}</td>
                                <td class="text-center">{{ $buku->jumlah }}</td>
                                <td>
                                    <a href="{{ route('buku.show',$buku->id) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="icon-eye text-white"></i>
                                    </a>
                                    <a href="{{ route('buku.edit',$buku->id) }}" class="btn btn-sm btn-warning" title="edit">
                                        <i class="mdi mdi-border-color text-white"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" title="Delete" onclick="confirmDelete('{{ $buku->id }}')">
                                        <i class="icon-trash text-white"></i>
                                    </button>
                                    <form id="delete-form-{{ $buku->id }}" action="{{ route('buku.destroy', $buku->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan {{ $bukus->firstItem() }} sampai {{ $bukus->lastItem() }} dari {{ $bukus->total() }} entri
                    </div>
                    <div>
                        {{ $bukus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
{{-- modal search --}}
<!-- Modal -->
<div class="modal fade" id="filter" tabindex="-1" aria-labelledby="filterLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form method="GET" action="{{ route('buku.index') }}" class="needs-validation" novalidate>
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title fs-5 fw-semibold" id="filterLabel">
            <i class="bi bi-sliders me-2"></i>Filter Buku
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Search Fields -->
          <div class="mb-3">
            <label for="judul" class="form-label fw-medium">Judul</label>
            <input type="text" name="judul" id="judul" class="form-control" 
                   placeholder="Cari berdasarkan judul" value="{{ request('judul') }}">
          </div>
          
          <div class="mb-3">
            <label for="penulis" class="form-label fw-medium">Penulis</label>
            <input type="text" name="penulis" id="penulis" class="form-control" 
                   placeholder="Cari berdasarkan penulis" value="{{ request('penulis') }}">
          </div>
          
          
          <!-- Kategori Selection -->
          <div class="mb-3">
            <label for="kategori_id" class="form-label fw-medium">Kategori</label>
            <select name="kategori_id" id="kategori_id" class="form-select">
              <option value="">Semua Kategori</option>
              @foreach($kategoris as $kategori)
                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                  {{ $kategori->nama }}
                </option>
              @endforeach
            </select>
          </div>
          
          <!-- Prodi Selection -->
          <div class="mb-3">
            <label for="prodi_id" class="form-label fw-medium">Program Studi</label>
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
        <div class="modal-footer">
          <button type="reset" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-funnel me-1"></i> Filter
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

