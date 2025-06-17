@extends('landing.app')

@section('title', 'Beranda')

@section('content')
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-content">
                    <h1 class="hero-title">Akses Pengetahuan Tanpa Batas</h1>
                    <p class="hero-subtitle">E-Library menyediakan koleksi digital terlengkap dengan akses 24 jam.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('prosedur') }}" class="btn btn-primary">Mulai Jelajahi</a>
                        <a href="{{ route('about') }}" class="btn btn-outline-light">Tentang Kami</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="section-title">Katalog</h2>
                <div class="row g-4 justify-content-center">
                    <div class="col-md-5">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <h3>Buku Fisik</h3>
                            <p>Lebih dari 100.000 judul buku digital dan fisik.</p>
                            <a href="{{ route('buku-fisik') }}" class="btn btn-primary mt-3">Lihat</a>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <h3>Ebook</h3>
                            <p>Perpustakaan digital dapat diakses kapan saja.</p>
                            <a href="{{ route('ebook') }}" class="btn btn-primary mt-3">Lihat</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection