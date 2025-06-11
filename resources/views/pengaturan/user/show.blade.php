@extends('layouts.app')
@section('title','Detail User')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" class="img-thumbnail" width="200">
                    @else
                        <img src="{{ asset('assets/images/profile_default.png') }}" class="img-thumbnail" width="200">
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>{{ $user->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
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
                        @if($user->prodi)
                        <tr>
                            <th>Program Studi</th>
                            <td>{{ $user->prodi->nama }} ({{ $user->prodi->kode }})</td>
                        </tr>
                        @endif
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
                    </table>
                </div>
            </div>
            
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary text-white">Kembali</a>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
        </div>
    </div>
</div>
@endsection