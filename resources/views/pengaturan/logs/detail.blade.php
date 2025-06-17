@extends('layouts.app')
@section('title', 'Detail Log Aktivitas')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i> Detail Log Aktivitas
                        </h5>
                        <a href="{{ route('logs.index') }}" class="btn btn-sm btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-light" style="width: 30%">ID Log</th>
                                    <td>{{ $log->id }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Waktu</th>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">User</th>
                                    <td>
                                        @if($log->user)
                                            {{ $log->user->username }} ({{ $log->user->nama_lengkap }})
                                        @else
                                            System
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">IP Address</th>
                                    <td>{{ $log->ip_address }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Aktivitas</th>
                                    <td>{{ $log->deskripsi }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-light d-flex justify-content-end">
                    <small class="text-muted">
                        Terakhir diupdate: {{ $log->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection