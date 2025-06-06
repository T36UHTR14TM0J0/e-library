<!-- resources/views/kategori/create.blade.php -->
@extends('layouts.app')
@section('title', 'Data Users')
@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-inverse-primary">
                <i class="icon-plus"></i> Tambah Data
            </a>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-inverse-dark btn-sm" data-bs-toggle="modal" data-bs-target="#filter">
              <i class="icon-search"></i> Filter
            </button>
            @if(request()->has('npm') || request()->has('nama_lengkap') || request()->has('username') || request()->has('role'))
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-inverse-danger">
                <i class="icon-close"></i> Batal Filter
            </a>
            @endif
        </div>

        <!-- Users Table -->
        <div class="card mt-2">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="bg-primary">
                            <tr>
                                <th class="text-white text-center bg-primary">No</th>
                                <th class="text-white text-center bg-primary">NPM / NIDN</th>
                                <th class="text-white text-center bg-primary">Nama Lengkap</th>
                                <th class="text-white text-center bg-primary">Username</th>
                                <th class="text-white text-center bg-primary">Role</th>
                                <th class="text-white text-center bg-primary">Tanggal Buat</th>
                                <th class="text-white text-center bg-primary">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no= 1;?>
                            @forelse ($users as $user)
                            <tr>
                                <td class="text-center"><?= $no++;?></td>
                                <td>
                                    @if($user->nidn || $user->npm)
                                        {{ $user->nidn ?? $user->npm }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $user->nama_lengkap }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $user->role == 'admin' ? 'danger' : 
                                        ($user->role == 'dosen' ? 'info' : 'success') 
                                    }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->locale('id')->translatedFormat('d F Y') }}</td>
                                <td>
                                    <a href="{{ route('users.show',$user->id) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="icon-eye text-white"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" title="Delete" onclick="confirmDelete('{{ $user->id }}')">
                                        <i class="icon-trash text-white"></i>
                                    </button>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
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
                        Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} dari {{ $users->total() }} entri
                    </div>
                    <div>
                        {{ $users->links() }}
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
      <form method="GET" action="{{ route('users.index') }}" class="needs-validation" novalidate>
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title fs-5 fw-semibold" id="filterLabel">
            <i class="bi bi-sliders me-2"></i>Filter Users
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Search Fields -->
          <div class="mb-3">
            <label for="npm" class="form-label fw-medium">NPM/NIDN</label>
            <input type="text" name="npm" id="npm" class="form-control" 
                   placeholder="Masukkan NPM atau NIDN" value="{{ request('npm') }}">
          </div>
          
          <div class="mb-3">
            <label for="nama_lengkap" class="form-label fw-medium">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" 
                   placeholder="Cari berdasarkan nama" value="{{ request('nama_lengkap') }}">
          </div>
          
          <div class="mb-3">
            <label for="username" class="form-label fw-medium">Username</label>
            <input type="text" name="username" id="username" class="form-control" 
                   placeholder="Cari username" value="{{ request('username') }}">
          </div>
          
          <!-- Role Selection -->
          <div class="mb-3">
            <label class="form-label fw-medium mb-2">Role</label>
            <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
              <input type="radio" class="btn-check" name="role" id="role_all" value="" 
                     autocomplete="off" {{ empty(request('role')) ? 'checked' : '' }}>
              <label class="btn btn-outline-primary" for="role_all">Semua</label>
              
              <input type="radio" class="btn-check" name="role" id="role_admin" value="admin" 
                     autocomplete="off" {{ request('role') == 'admin' ? 'checked' : '' }}>
              <label class="btn btn-outline-danger" for="role_admin">Admin</label>
              
              <input type="radio" class="btn-check" name="role" id="role_dosen" value="dosen" 
                     autocomplete="off" {{ request('role') == 'dosen' ? 'checked' : '' }}>
              <label class="btn btn-outline-info" for="role_dosen">Dosen</label>
              
              <input type="radio" class="btn-check" name="role" id="role_mahasiswa" value="mahasiswa" 
                     autocomplete="off" {{ request('role') == 'mahasiswa' ? 'checked' : '' }}>
              <label class="btn btn-outline-success" for="role_mahasiswa">Mahasiswa</label>
            </div>
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
