@extends('landing.app')

@section('title', 'Jam Pelayanan')

@section('content')
<section class="py-5 mt-5">
    <div class="container py-5">
        <h2 class="section-title text-center">Jam Pelayanan</h2>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="schedule-table">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Jam Buka</th>
                                <th>Jam Tutup</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hours as $hour)
                            <tr>
                                <td>{{ $hour->hari }}</td>
                                <td>{{ \Carbon\Carbon::parse($hour->waktu_buka)->format('H:i') . ' WIB' }}</td>
                                <td>{{ \Carbon\Carbon::parse($hour->waktu_tutup)->format('H:i') . ' WIB'}}</td>
                                <td>{{ $hour->catatan ?? 'Layanan penuh' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 text-center">
                    <p class="text-muted">* Perpustakaan digital dapat diakses 24 jam</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection