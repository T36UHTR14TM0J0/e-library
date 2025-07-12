@extends('landing.app')

@section('title', 'Peraturan Perpustakaan')

@section('content')
<section class="py-5" style="background-color: #f0c9c9">
    <div class="container py-5 mt-5">
        <!-- Library Rules Header Section -->
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-dark mb-3">Peraturan Perpustakaan</h2>
            <p class="lead text-muted">Untuk kenyamanan bersama, mohon patuhi peraturan berikut selama berada di perpustakaan</p>
            <div class="mx-auto" style="width: 80px; height: 3px; background: linear-gradient(90deg, #0d6efd, #6c757d);"></div>
        </div>
        
        <div class="row g-4 justify-content-center">
            <!-- General Rules Card -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-wrapper bg-danger bg-opacity-10 me-3">
                                <i class="fas fa-info-circle fa-2x text-danger"></i>
                            </div>
                            <h3 class="h4 mb-0 text-dark">Peraturan Umum</h3>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach($peraturanUmum as $peraturan)
                            <li class="list-group-item border-0 ps-0 py-3 d-flex">
                                <i class="fas fa-circle text-danger me-3 mt-1"></i>
                                <span>{{ $peraturan->text }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Reading Room Rules Card -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-wrapper bg-primary bg-opacity-10 me-3">
                                <i class="fas fa-book-open fa-2x text-primary"></i>
                            </div>
                            <h3 class="h4 mb-0 text-dark">Peraturan Ruang Baca</h3>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach($peraturanRuangBaca as $peraturan)
                            <li class="list-group-item border-0 ps-0 py-3 d-flex">
                                <i class="fas fa-circle text-primary me-3 mt-1"></i>
                                <span>{{ $peraturan->text }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .icon-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 12px;
    }
    .list-group-item {
        background-color: transparent;
    }
    .card {
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    section {
        background: linear-gradient(135deg, #ffcccc 0%, #f1c2c2 100%);
    }
</style>
@endsection