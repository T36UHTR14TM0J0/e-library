@extends('landing.app')

@section('title', 'Layanan Sirkulasi')

@section('content')
<section class="py-5 bg-light mt-5">
    <div class="container py-5">
        <h2 class="section-title text-center">Layanan Sirkulasi</h2>
        
        <div class="row g-4">
            @foreach($layanans as $layanan)
            <div class="col-md-6 col-lg-3">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="{{ $layanan->icon }}"></i>
                    </div>
                    <h4>{{ $layanan->nama }}</h4>
                    <p>{{ $layanan->deskripsi }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection