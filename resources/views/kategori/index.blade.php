<!-- resources/views/user/user.blade.php -->

@extends('layouts.app')
@section('title', 'Data Kategori')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('kategori.create') }}" class="btn btn-sm btn-inverse-primary">
                <i class="icon-plus"></i> Tambah Data
            </a>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-inverse-dark btn-sm" data-bs-toggle="modal" data-bs-target="#filter">
              <i class="icon-search"></i> Filter
            </button>
            @if(request()->has('nama'))
            <a href="{{ route('kategori.index') }}" class="btn btn-sm btn-inverse-danger">
                <i class="icon-close"></i> Batal Filter
            </a>
            @endif
        </div>
        <div class="card mt-2">
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-striped table-hover">
                      <thead class="bg-primary">
                          <tr>
                              <th class="text-white bg-primary text-center" style="width: 5%">No</th>
                              <th class="text-white bg-primary text-center" style="width: 30%">Nama Kategori</th>
                              <th class="text-white bg-primary text-center">Deskripsi</th>
                              <th class="text-white bg-primary text-center" style="width: 20%">Aksi</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php $no= 1;?>
                          @forelse ($kategori as $row)
                          <tr>
                              <td class="text-center"><?= $no++;?></td>
                              <td>{{ $row->nama }}</td>
                              <td>{{ ($row->deskripsi) ? $row->deskripsi : "-" }}</td>
                              <td class="text-center">
                                  <a href="{{ route('kategori.edit',$row->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                      <i class="mdi mdi-border-color"></i>
                                  </a>

                                  <button class="btn btn-sm btn-danger" title="Delete" onclick="confirmDelete('{{ $row->id }}')">
                                      <i class="icon-trash text-white"></i>
                                  </button>
                                  <form id="delete-form-{{ $row->id }}" action="{{ route('kategori.destroy', $row->id) }}" method="POST" style="display: none;">
                                      @csrf
                                      @method('DELETE')
                                  </form>
                              </td>
                          </tr>
                          @empty
                          <tr>
                              <td colspan="4" class="text-center">Tidak ada data</td>
                          </tr>
                          @endforelse
                      </tbody>
                  </table>
              </div>

              <!-- Pagination -->
              <div class="d-flex justify-content-between align-items-center mt-3">
                  <div>
                      Menampilkan {{ $kategori->firstItem() }} sampai {{ $kategori->lastItem() }} dari {{ $kategori->total() }} entri
                  </div>
                  <div>
                      {{ $kategori->links() }}
                  </div>
              </div>
          </div>
      </div>
    </div>

    <!-- Users Table -->
    
</div>



{{-- modal search --}}
<!-- Modal -->
<div class="modal fade" id="filter" tabindex="-1" aria-labelledby="filterLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="GET" action="{{ route('kategori.index') }}">
        <div class="modal-header">
          <h5 class="modal-title" id="filterLabel">Filter Kategori</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="nama">Nama Kategori</label>
            <input type="text" name="nama" id="nama" name="nama" class="form-control">
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
