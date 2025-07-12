@extends('layouts.app')
@section('title', 'Detail Galeri Perpustakaan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">                
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($galeri->foto)
                            <img src="{{ asset('storage/' . $galeri->foto) }}" 
                                 alt="{{ $galeri->judul }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 200px;">
                        @else
                            <div class="bg-light p-5 text-muted rounded">
                                <i class="bi bi-image" style="font-size: 3rem;"></i>
                                <p class="mt-2">Tidak ada gambar</p>
                            </div>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Urutan:</div>
                        <div class="col-md-8">{{ $galeri->urut }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Judul:</div>
                        <div class="col-md-8">{{ $galeri->judul }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Deskripsi:</div>
                        <div class="col-md-8">{!! nl2br(e($galeri->deskripsi)) !!}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Tanggal Dibuat:</div>
                        <div class="col-md-8">{{ $galeri->created_at->locale('id')->translatedFormat('d F Y H:i') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 fw-bold">Terakhir Diupdate:</div>
                        <div class="col-md-8">{{ $galeri->updated_at->locale('id')->translatedFormat('d F Y H:i') }}</div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-start">
                        <a href="{{ route('galeri.index') }}" class="btn btn-sm btn-secondary text-white">
                             Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endpush

@endsection