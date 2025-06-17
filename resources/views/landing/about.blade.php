@extends('landing.app')

@section('title', 'Tentang Kami')

@section('content')
<section class="py-5 mt-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Tentang Kami" class="img-fluid rounded about-img">
            </div>
            <div class="col-lg-6">
                <h2 class="section-title">Tentang Kami</h2>
                <p>E-Library adalah perpustakaan digital modern yang didirikan pada tahun 2020.</p>
                
                <div class="mt-4">
                    <h5 class="fw-bold">Visi Kami:</h5>
                    <p>{{ $about['vision'] }}</p>
                    
                    <h5 class="fw-bold mt-3">Misi Kami:</h5>
                    <ul class="ps-3">
                        @foreach($about['mission'] as $mission)
                        <li>{{ $mission }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection