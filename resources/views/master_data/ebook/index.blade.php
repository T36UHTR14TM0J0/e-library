@extends('layouts.app')
@section('title', 'Data Ebook')
@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('ebook.create') }}" class="btn btn-sm btn-inverse-primary">
                <i class="icon-plus"></i> Tambah Data
            </a>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-inverse-dark btn-sm" data-bs-toggle="modal" data-bs-target="#filter">
              <i class="icon-search"></i> Filter
            </button>
            @if(request()->has('judul') || request()->has('penulis') || request()->has('kategori_id') || request()->has('prodi_id'))
            <a href="{{ route('ebook.index') }}" class="btn btn-sm btn-inverse-danger">
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
                                <th class="text-white text-center bg-primary">Judul</th>
                                <th class="text-white text-center bg-primary">Penulis</th>
                                <th class="text-white text-center bg-primary">Kategori</th>
                                <th class="text-white text-center bg-primary">Prodi</th>
                                <th class="text-white text-center bg-primary">Uploader</th>
                                <th class="text-white text-center bg-primary">Izin Unduh</th>
                                <th class="text-white text-center bg-primary">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no= 1;?>
                            @forelse ($ebooks as $ebook)
                            <tr>
                                <td class="text-center"><?= $no++;?></td>
                                <td>{{ $ebook->judul }}</td>
                                <td>{{ $ebook->penulis }}</td>
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
                                <td>
                                    <a href="{{ route('ebook.show',$ebook->id) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="icon-eye text-white"></i>
                                    </a>
                                    <a href="{{ route('ebook.edit',$ebook->id) }}" class="btn btn-sm btn-warning" title="edit">
                                        <i class="mdi mdi-border-color text-white"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" title="Delete" onclick="confirmDelete('{{ $ebook->id }}')">
                                        <i class="icon-trash text-white"></i>
                                    </button>
                                    <form id="delete-form-{{ $ebook->id }}" action="{{ route('ebook.destroy', $ebook->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
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
{{-- modal search --}}
<!-- Modal -->
<div class="modal fade" id="filter" tabindex="-1" aria-labelledby="filterLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form method="GET" action="{{ route('ebook.index') }}" class="needs-validation" novalidate>
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title fs-5 fw-semibold" id="filterLabel">
            <i class="bi bi-sliders me-2"></i>Filter ebook
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

          <!-- Izin Unduh Selection -->
          <div class="mb-3">
            <label for="izin_unduh" class="form-label fw-medium">Izin Unduh</label>
            <select name="izin_unduh" id="izin_unduh" class="form-select">
              <option value="">Semua Status</option>
              <option value="1" {{ request('izin_unduh') == '1' ? 'selected' : '' }}>Diizinkan</option>
              <option value="0" {{ request('izin_unduh') == '0' ? 'selected' : '' }}>Tidak Diizinkan</option>
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
