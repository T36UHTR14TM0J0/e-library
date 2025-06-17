@extends('landing.app')

@section('title', 'Prosedur Kunjungan')

@section('content')
<section class="py-5 bg-light mt-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6">
                <h2 class="section-title">Prosedur Kunjungan</h2>
                <p class="mb-4">Ikuti langkah-langkah berikut untuk mengakses layanan perpustakaan kami:</p>
                
                <ul class="prosedur-list">
                    @foreach($prosedurs as $prosedur)
                    <li>
                        <div class="prosedur-number">{{ $prosedur->kode }}</div>
                        <div>
                            <h5>{{ $prosedur->judul }}</h5>
                            <p class="mb-0">{{ $prosedur->deskripsi }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Prosedur Kunjungan" class="img-fluid rounded about-img">
            </div>
        </div>
    </div>
</section>
@endsection