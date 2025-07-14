@extends('layouts.app')
@section('title','Laporan Anggota')
@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-filter-square me-2"></i> Filter Laporan Anggota</h5>
                @if(request()->hasAny(['role', 'prodi_id', 'search']))
                <a href="{{ route('laporan.anggota.index') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.anggota.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="role" class="form-label">Role Anggota</label>
                        <select name="role" id="role" class="form-select form-select-sm">
                            <option value="">Semua Role</option>
                            <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
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
                        <label for="search" class="form-label">Cari (Nama/NPM/NIDN)</label>
                        <input type="text" name="search" id="search" class="form-control form-control-sm" 
                               value="{{ request('search') }}" placeholder="Masukkan pencarian...">
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <button type="submit" class="btn btn-sm btn-dark">
                        <i class="bi bi-funnel me-1"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('laporan.anggota.export.pdf', request()->query()) }}" class="btn btn-sm text-white btn-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                    </a>
                    <a href="{{ route('laporan.anggota.export.excel', request()->query()) }}" class="btn btn-sm text-white btn-success">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white border-bottom py-3">
            <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i> Data Anggota</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50" class="bg-primary text-center text-white">#</th>
                            <th class="bg-primary text-white">Nama Lengkap</th>
                            <th class="bg-primary text-center text-white">Role</th>
                            <th class="bg-primary text-center text-white">NPM/NIDN</th>
                            <th class="bg-primary text-white">Program Studi</th>
                            <th class="bg-primary text-white">Email</th>
                            <th class="bg-primary text-center text-white">Username</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $key => $user)
                        <tr class="align-middle">
                            <td class="text-center">{{ $users->firstItem() + $key }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- @if($user->foto)
                                        <img src="{{ asset('storage/'.$user->foto) }}" 
                                             alt="{{ $user->nama_lengkap }}" 
                                             class="rounded-circle me-3" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center rounded-circle me-3" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif --}}
                                    <div>
                                        <h6 class="mb-0">{{ $user->nama_lengkap }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $badgeClass = [
                                        'admin' => 'bg-danger',
                                        'dosen' => 'bg-primary',
                                        'mahasiswa' => 'bg-success'
                                    ][$user->role];
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{ $user->role == 'mahasiswa' ? $user->npm : $user->nidn }}
                            </td>
                            <td>{{ $user->prodi ? $user->prodi->nama : '-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">{{ $user->username }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">Tidak ada data anggota</h4>
                                    <p class="text-muted">Coba gunakan filter yang berbeda</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="2" class="text-end">Total</th>
                            <th class="text-center">{{ $users->where('role', 'dosen')->count() }} Dosen</th>
                            <th class="text-center">{{ $users->where('role', 'mahasiswa')->count() }} Mahasiswa</th>
                            <th colspan="3" class="text-end">{{ $users->total() }} Total Anggota</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        @if($users->hasPages())
        <div class="card-footer bg-light">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted mb-2 mb-md-0">
                    Menampilkan <strong>{{ $users->firstItem() }}</strong> sampai <strong>{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> entri
                </div>
                <div>
                    {{ $users->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection