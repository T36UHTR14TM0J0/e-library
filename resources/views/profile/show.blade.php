@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Profil Saya</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <center>
                              @if($user->foto)
                                <img src="{{ asset('storage/' . $user->foto) }}" 
                                     class="img-thumbnail rounded-circle mb-3" 
                                     width="200" height="200" alt="Foto Profil">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mb-3" 
                                     style="width: 200px; height: 200px;">
                                     <img src="{{ asset('assets/images/profile_default.png') }}" 
                                     class="img-thumbnail rounded-circle mb-3" 
                                     width="200" height="200" alt="Foto Profil">
                                </div>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                                <i class="icon-pencil"></i> Edit Profil
                            </a>
                            </center>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                @if($user->npm)
                                <tr>
                                    <th>NPM</th>
                                    <td>{{ $user->npm }}</td>
                                </tr>
                                @endif
                                @if($user->nidn)
                                <tr>
                                    <th>NIDN</th>
                                    <td>{{ $user->nidn }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th width="30%">Nama Lengkap</th>
                                    <td>{{ $user->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>
                                        {{ $user->email }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td>{{ $user->username }}</td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'dosen' ? 'info' : 'success') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($user->prodi_id)
                                <tr>
                                    <th>Program Studi</th>
                                    <td>{{ $user->prodi->nama . " ( " . $user->prodi->kode . " )" ?? 'Data prodi tidak ditemukan' }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Bergabung Pada</th>
                                    <td>{{ $user->created_at->format('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Diperbarui</th>
                                    <td>{{ $user->updated_at->format('d F Y H:i') }}</td>
                                </tr>
                            </table>
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
        });
    });
</script>
@endpush