@extends('layouts.app')
@section('title', 'Detail Ebook')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    @if($ebook->gambar_sampul)
                        <img src="{{ asset('storage/' . $ebook->gambar_sampul) }}" class="img-thumbnail" width="200" alt="Cover Ebook">
                    @else
                        <img src="{{ asset('assets/images/default-cover.png') }}" class="img-thumbnail" width="200" alt="Cover Default">
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Judul</th>
                            <td>{{ $ebook->judul }}</td>
                        </tr>
                        <tr>
                            <th>Penulis</th>
                            <td>{{ $ebook->penulis }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>
                                @if($ebook->kategori)
                                    <span class="badge bg-info">{{ $ebook->kategori->nama }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Program Studi</th>
                            <td>
                                @if($ebook->prodi)
                                    {{ $ebook->prodi->nama }} ({{ $ebook->prodi->kode }})
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Format File</th>
                            <td>
                                @if($ebook->file_url)
                                    {{ strtoupper(pathinfo($ebook->file_url, PATHINFO_EXTENSION)) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ukuran File</th>
                            <td>
                                @if($ebook->file_url && Storage::disk('public')->exists($ebook->file_url))
                                    {{ round(Storage::disk('public')->size($ebook->file_url) / 1024 / 1024, 2) }} MB
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Izin Unduh</th>
                            <td>
                                <span class="badge bg-{{ $ebook->izin_unduh ? 'success' : 'danger' }}">
                                    {{ $ebook->izin_unduh ? 'Diizinkan' : 'Tidak Diizinkan' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $ebook->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                        </tr>
                        <tr>
                            <th>Diunggah Oleh</th>
                            <td>
                                @if($ebook->pengunggah)
                                    {{ $ebook->pengunggah->nama_lengkap }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Diunggah</th>
                            <td>{{ $ebook->created_at->locale('id')->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Diupdate</th>
                            <td>{{ $ebook->updated_at->locale('id')->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Total Dibaca</th>
                            <td>{{ $ebook->total_reads }} kali</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('KatalogEbook.index') }}" class="btn btn-secondary text-white">
                    Kembali ke Daftar Ebook
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
