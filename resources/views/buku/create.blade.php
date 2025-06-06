@extends('layouts.app')
@section('title', 'Tambah Buku')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('buku.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Informasi Utama Buku -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Informasi Utama</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="judul" class="form-label">Judul Buku <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Masukkan judul buku">
                                    @error('judul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('penulis') is-invalid @enderror" id="penulis" name="penulis" value="{{ old('penulis') }}" placeholder="Nama penulis">
                                    @error('penulis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="penerbit" class="form-label">Penerbit <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('penerbit') is-invalid @enderror" id="penerbit" name="penerbit" value="{{ old('penerbit') }}" placeholder="Nama penerbit">
                                    @error('penerbit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="isbn" class="form-label">ISBN</label>
                                    <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn') }}" placeholder="Nomor ISBN (opsional)">
                                    @error('isbn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Informasi Tambahan</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="tahun_terbit" class="form-label">Tahun Terbit <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('tahun_terbit') is-invalid @enderror" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit') }}" min="1900" max="{{ date('Y') }}" placeholder="Tahun terbit">
                                    @error('tahun_terbit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}" min="1">
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="kategori_id" class="form-label">Kategori</label>
                                    <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="prodi_id" class="form-label">Program Studi</label>
                                    <select class="form-select @error('prodi_id') is-invalid @enderror" id="prodi_id" name="prodi_id">
                                        <option value="">Pilih Program Studi</option>
                                        @foreach($prodis as $prodi)
                                            <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('prodi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="gambar_sampul" class="form-label">Gambar Sampul</label>
                                    <input type="file" class="form-control @error('gambar_sampul') is-invalid @enderror" id="gambar_sampul" name="gambar_sampul" accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG (Maks. 2MB)</small>
                                    @error('gambar_sampul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="image-preview" class="mt-2"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Deskripsi</h6>
                            <div class="form-group">
                                <label for="deskripsi" class="form-label">Deskripsi Buku</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi buku">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('buku.index') }}" class="btn btn-secondary text-white">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Simpan Buku
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Image preview handler
    document.getElementById('gambar_sampul').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        if (!file.type.match('image.*')) {
            alert('Harap pilih file gambar');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = `
                <div class="border p-2 rounded" style="max-width: 200px;">
                    <img src="${e.target.result}" class="img-fluid" alt="Preview">
                    <div class="text-center small mt-1">${file.name}</div>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush