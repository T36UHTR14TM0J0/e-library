@extends('layouts.app')
@section('title', 'Tambah Ebook')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('ebook.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Informasi Utama Ebook -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Informasi Utama</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="judul" class="form-label">Judul Ebook <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Masukkan judul ebook">
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
                                    <select class="form-select @error('penerbit_id') is-invalid @enderror" id="penerbit_id" name="penerbit_id">
                                        <option value="">Pilih Penerbit</option>
                                        @foreach($penerbits as $penerbit)
                                            <option value="{{ $penerbit->id }}" {{ old('penerbit_id') == $penerbit->id ? 'selected' : '' }}>{{ $penerbit->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('penerbit_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
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
                                        <option value="">Pilih Program Studi (opsional)</option>
                                        @foreach($prodis as $prodi)
                                            <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('prodi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">File Ebook</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="file_url" class="form-label">File Ebook <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('file_url') is-invalid @enderror" id="file_url" name="file_url" accept=".pdf,.epub">
                                    <small class="text-muted">Format: PDF, EPUB (Maks. 10MB)</small>
                                    @error('file_url')
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

                                <div class="col-12 mt-3">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check me-3">
                                            <input class="form-check" type="checkbox" id="izin_unduh" name="izin_unduh" value="1" {{ old('izin_unduh') ? 'checked' : '' }} style="margin-top: 0;">
                                        </div>
                                        <div>
                                            <label class="form-check" for="izin_unduh">
                                                Izinkan pengunduhan
                                            </label>
                                            <small class="d-block text-muted">Centang untuk mengizinkan pengguna mengunduh ebook ini</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Deskripsi</h6>
                            <div class="form-group">
                                <label for="deskripsi" class="form-label">Deskripsi Ebook</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi ebook">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('ebook.index') }}" class="btn btn-secondary text-white">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Simpan Ebook
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