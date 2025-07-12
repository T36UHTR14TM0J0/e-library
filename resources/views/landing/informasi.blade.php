@extends('landing.app')

@section('title', 'Informasi')

@section('content')
<section class="py-5 bg-light bg-gradient">
    <div class="container py-5">
        <!-- Reading Room Header Section -->
        <div class="text-center mb-5 mt-5">
            <h2 class="display-5 fw-bold text-dark mb-3">Informasi Perpustakaan</h2>
            <div class="mx-auto" style="width: 80px; height: 3px; background: linear-gradient(90deg, #0d6efd, #6c757d);"></div>
        </div>
        
        <div class="row g-4">
            @foreach($informasi as $informasi)
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                        <div class="card-header bg-{{ $informasi->warna }} bg-opacity-10 border-0 py-3 text-center">
                            <div class="icon-wrapper mb-3">
                                <i class="fas {{ $informasi->icon }} fa-3x text-{{ $informasi->warna }}"></i>
                            </div>
                            <h3 class="h4 mb-0 text-dark">
                                {{ $informasi->judul }}
                            </h3>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <ul class="list-unstyled mb-4">
                                <li class="mb-3 d-flex">
                                    <i class="fas fa-users text-success me-3 mt-1"></i>
                                    <span class="text-dark"><strong>Kapasitas:</strong> {{ $informasi->min_kapasitas }} - {{ $informasi->maks_kapasitas }} Orang</span>
                                </li>
                                <li class="mb-3 d-flex">
                                    <i class="fas fa-clock text-success me-3 mt-1"></i>
                                    <span class="text-dark"><strong>Buka:</strong> {{ date('h:i', strtotime($informasi->waktu_buka)) }} - {{ date('h:i', strtotime($informasi->waktu_tutup)) . 'WIB' }}</span>
                                </li>
                                @if($informasi->info)
                                <li class="mb-3 d-flex">
                                    <i class="fas fa-info-circle text-success me-3 mt-1"></i>
                                    <span class="text-dark">{{ $informasi->info }}</span>
                                </li>
                                @endif
                            </ul>
                            
                            <h4 class="h5 fw-bold text-dark mb-3">
                                Fasilitas:
                            </h4>
                            <div class="row">
                                @php
                                    $informasiItems = explode(', ', $informasi->fasilitas);
                                    $half = ceil(count($informasiItems) / 2);
                                @endphp
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        @foreach(array_slice($informasiItems, 0, $half) as $item)
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="fas fa-circle text-{{ $informasi->warna }} me-2" style="font-size: 8px;"></i>
                                            <span class="text-muted">{{ $item }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        @foreach(array_slice($informasiItems, $half) as $item)
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="fas fa-circle text-{{ $informasi->warna }} me-2" style="font-size: 8px;"></i>
                                            <span class="text-muted">{{ $item }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .hover-shadow {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .bg-gradient {
        background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
    }
    .icon-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        border-radius: 50%;
    }
</style>
@endsection