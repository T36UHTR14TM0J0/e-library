@extends('layouts.app')
@section('title', 'Detail Informasi: ' . $informasi->judul)

@section('content')
<div class="container">
    <div class="card shadow-sm">        
        <div class="card-body">
            <!-- Header dengan icon dan warna -->
            <div class="text-center mb-4">
                <div class="icon-wrapper d-inline-block p-3 rounded-circle bg-{{ $informasi->warna }} text-white mb-3">
                    <i class="fas {{ $informasi->icon }} fa-2x"></i>
                </div>
                <h3 class="text-{{ $informasi->warna }}">{{ $informasi->judul }}</h3>
                @if($informasi->info)
                    <p class="text-muted">{{ $informasi->info }}</p>
                @endif
            </div>

            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Informasi Kapasitas</h5>
                        <div class="row">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block">Minimum</small>
                                    <strong>{{ $informasi->min_kapasitas }} orang</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block">Maksimum</small>
                                    <strong>{{ $informasi->maks_kapasitas }} orang</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Jam Operasional</h5>
                        <div class="row">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block">Buka</small>
                                    <strong>{{ $waktu_buka }}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block">Tutup</small>
                                    <strong>{{ $waktu_tutup }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Fasilitas Tersedia</h5>
                        @if(count($fasilitas) > 0)
                            <ul class="list-group">
                                @foreach($fasilitas as $item)
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="alert alert-warning">
                                Tidak ada fasilitas yang tercatat
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer text-muted">
            <a href="{{ route('informasi.index') }}" class="btn btn-sm btn-secondary me-2 text-white">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
        </div>
    </div>
</div>

<style>
    .icon-wrapper {
        transition: transform 0.3s ease;
    }
    .icon-wrapper:hover {
        transform: scale(1.1);
    }
    .bg-secondary { background-color: #6c757d !important; }
    .bg-info { background-color: #0dcaf0 !important; }
    .bg-success { background-color: #198754 !important; }
    .bg-warning { background-color: #ffc107 !important; }
    .bg-danger { background-color: #dc3545 !important; }
    .text-secondary { color: #6c757d !important; }
    .text-info { color: #0dcaf0 !important; }
    .text-success { color: #198754 !important; }
    .text-warning { color: #ffc107 !important; }
    .text-danger { color: #dc3545 !important; }
</style>
@endsection