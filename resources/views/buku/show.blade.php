@extends('layouts.app')
@section('title', 'Detail Buku')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    @if($buku->cover)
                        <img src="{{ asset('storage/' . $buku->cover) }}" class="img-thumbnail" width="200" alt="Cover Buku">
                    @else
                        <img src="{{ asset('assets/images/default-cover.png') }}" class="img-thumbnail" width="200" alt="Cover Default">
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">ISBN</th>
                            <td>{{ $buku->isbn ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Judul</th>
                            <td>{{ $buku->judul }}</td>
                        </tr>
                        <tr>
                            <th>Penulis</th>
                            <td>{{ $buku->penulis }}</td>
                        </tr>
                        <tr>
                            <th>Penerbit</th>
                            <td>{{ $buku->penerbit ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tahun Terbit</th>
                            <td>{{ $buku->tahun_terbit ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>
                                @if($buku->kategori)
                                    <span class="badge bg-info">{{ $buku->kategori->nama }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Program Studi</th>
                            <td>
                                @if($buku->prodi)
                                    {{ $buku->prodi->nama }} ({{ $buku->prodi->kode }})
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Jumlah Stok</th>
                            <td>
                                <span class="badge bg-{{ $buku->jumlah > 0 ? 'success' : 'danger' }}">
                                    {{ $buku->jumlah }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $buku->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Total</th>
                            <td>{{ $buku->jumlah }}</td>
                        </tr>
                        <tr>
                            <th>Sedang Dipinjam</th>
                            <td>{{ $buku->jumlah - $buku->jumlahTersedia() }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Tersedia</th>
                            <td>
                                <span class="badge bg-{{ $buku->jumlahTersedia() > 0 ? 'success' : 'danger' }}">
                                    {{ $buku->jumlahTersedia() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status Ketersediaan</th>
                            <td>
                                <span class="badge bg-{{ $buku->tersedia() ? 'success' : 'danger' }}">
                                    {{ $buku->tersedia() ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Ditambahkan</th>
                            <td>{{ $buku->created_at }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Diupdate</th>
                            <td>{{ $buku->updated_at }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('buku.index') }}" class="btn btn-secondary text-white">
                     Kembali ke Daftar Buku
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush