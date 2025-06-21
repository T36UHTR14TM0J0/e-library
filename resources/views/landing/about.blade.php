@extends('landing.app')

@section('title', 'Tentang Kami')

@section('content')
<section class="py-5 mt-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.7803798696145!2d100.6248666735795!3d-0.24986923536862968!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e2ab4d5980c5bdf%3A0xe75924bdfae385b8!2sSTT%20Payakumbuh!5e0!3m2!1sid!2sid!4v1750222125071!5m2!1sid!2sid" width="500" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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

<!-- Map Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="section-title">Kontak Kami</h2>
                <p class="lead">Kunjungi perpustakaan kami di lokasi berikut</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4 text-center">
                <div class="p-3">
                    <i class="fas fa-map-marker-alt fa-2x text-primary mb-3"></i>
                    <h5>Alamat</h5>
                    <p>Jalan Khatib Sulaiman Sawah Padang Kelurahan Sawah Padang Aur Kuning Payakumbuh Selatan Kota Payakumbuh Sumatera Barat</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="p-3">
                    <i class="fas fa-clock fa-2x text-primary mb-3"></i>
                    <h5>Jam Operasional</h5>
                    <p>Senin-Minggu: 08.00 - 18.00</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="p-3">
                    <i class="fas fa-phone-alt fa-2x text-primary mb-3"></i>
                    <h5>Kontak</h5>
                    <p>Telp: (022) 1234567<br>Email: info@elibrary.com</p>
                    
                    <!-- Social Media Icons -->
                    <div class="mt-3">
                        <h5 class="mb-3">Media Sosial</h5>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="https://www.facebook.com/sekolahtinggiteknologipayakumbuh" target="_blank" class="text-decoration-none">
                                <i class="fab fa-facebook-f fa-2x text-primary"></i>
                            </a>
                            <a href="https://www.instagram.com/stt_payakumbuh_official/" target="_blank" class="text-decoration-none">
                                <i class="fab fa-instagram fa-2x text-danger"></i>
                            </a>
                            <a href="https://www.youtube.com/@sttpayakumbuh" target="_blank" class="text-decoration-none">
                                <i class="fab fa-youtube fa-2x text-danger"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection