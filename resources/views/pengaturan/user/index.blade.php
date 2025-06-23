@extends('layouts.app')
@section('title', 'Data Users')
@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12 my-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="mb-0"><i class="bi bi-funnel me-2"></i> Filter Data Pengguna</h5>
                      <a href="{{ route('users.create') }}" class="btn btn-sm btn-light">
                          <i class="bi bi-plus-circle me-1"></i> Tambah User
                      </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('users.index') }}" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="npm" class="form-label fw-medium">NPM/NIDN</label>
                                <input type="text" name="npm" id="npm" class="form-control form-control-sm" placeholder="Masukkan NPM atau NIDN" value="{{ request('npm') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="username" class="form-label fw-medium">Username</label>
                                <input type="text" name="username" id="username" class="form-control form-control-sm" placeholder="Cari username" value="{{ request('username') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="role" class="form-label fw-medium">Role</label>
                                <select class="form-select form-select-sm" name="role" id="role">
                                    <option value="" {{ empty(request('role')) ? 'selected' : '' }}>Semua Role</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                    <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status_aktif" class="form-label fw-medium">Status</label>
                                <select class="form-select form-select-sm" name="status_aktif" id="status_aktif">
                                    <option value="" {{ empty(request('status_aktif')) ? 'selected' : '' }}>Semua Status</option>
                                    <option value="1" {{ request('status_aktif') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ request('status_aktif') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                {{-- <a href="{{ route('users.create') }}" class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah User
                                </a> --}}
                            </div>
                            <div>
                                @if(request()->has('npm') || request()->has('nama_lengkap') || request()->has('username') || request()->has('role') || request()->has('status_aktif'))
                                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                                    <i class="bi bi-x-circle me-1"></i> Reset
                                </a>
                                @endif
                                <button type="submit" class="btn btn-sm btn-dark text-white">
                                    <i class="bi bi-funnel me-1"></i> Terapkan Filter
                                </button>
                            </div>
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
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i> Daftar Users</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" style="font-size: 0.875rem">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center bg-primary text-white" width="5%">No</th>
                                    <th class="text-center bg-primary text-white">NPM/NIDN</th>
                                    <th class="text-center bg-primary text-white">Username</th>
                                    <th class="text-center bg-primary text-white">Role</th>
                                    <th class="text-center bg-primary text-white">Status</th>
                                    <th class="text-center bg-primary text-white">Tanggal Buat</th>
                                    <th class="text-center bg-primary text-white" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                    <td class="text-center">
                                        {{ $user->nidn ?? $user->npm ?? '-' }}
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-{{ 
                                            $user->role == 'admin' ? 'danger' : 
                                            ($user->role == 'dosen' ? 'info' : 'success') 
                                        }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-{{ 
                                            $user->status_aktif == '0' ? 'danger' : 'success' 
                                        }}">
                                            {{ $user->status_aktif == '1' ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $user->created_at->locale('id')->translatedFormat('d M Y') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('users.show',$user->id) }}" class="btn btn-info text-white" title="View" data-bs-toggle="tooltip">
                                                Detail
                                            </a>
                                            <a href="{{ route('users.edit',$user->id) }}" class="btn btn-warning text-white" title="Edit" data-bs-toggle="tooltip">
                                                Edit
                                            </a>
                                            <button class="btn btn-danger text-white" title="Delete" onclick="confirmDelete('{{ $user->id }}')" data-bs-toggle="tooltip">
                                                Hapus
                                            </button>
                                            <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">Tidak ada data pengguna</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Menampilkan <strong>{{ $users->firstItem() }}</strong> sampai <strong>{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> entri
                        </div>
                        <div>
                            {{ $users->links('vendor.pagination.bootstrap-5') }}
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