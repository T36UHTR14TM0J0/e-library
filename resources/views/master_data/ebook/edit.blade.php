@extends('layouts.app')
@section('title', 'Edit Ebook')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('ebook.update', $ebook->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Informasi Utama Ebook -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Informasi Utama</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="judul" class="form-label">Judul Ebook <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $ebook->judul) }}" placeholder="Masukkan judul ebook">
                                    @error('judul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('penulis') is-invalid @enderror" id="penulis" name="penulis" value="{{ old('penulis', $ebook->penulis) }}" placeholder="Nama penulis">
                                    @error('penulis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="penerbit" class="form-label">Penerbit <span class="text-danger">*</span></label>
                                    <select class="form-select @error('penerbit_id') is-invalid @enderror" id="penerbit_id" name="penerbit_id">
                                        <option value="">Pilih Penerbit</option>
                                        @foreach($penerbits as $penerbit)
                                            <option value="{{ $penerbit->id }}" {{ old('penerbit_id', $ebook->penerbit_id) == $penerbit->id ? 'selected' : '' }}>{{ $penerbit->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}" {{ old('kategori_id', $ebook->kategori_id) == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
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
                                            <option value="{{ $prodi->id }}" {{ old('prodi_id', $ebook->prodi_id) == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('prodi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- File Ebook -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">File Ebook</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="file_url" class="form-label">File Ebook</label>
                                    <input type="file" class="form-control @error('file_url') is-invalid @enderror" id="file_url" name="file_url" accept=".pdf,.epub">
                                    <small class="text-muted">Format: PDF, EPUB (Maks. 10MB)</small>
                                    @error('file_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($ebook->file_url)
                                        <div class="mt-2">
                                            <div class="border p-2 rounded">
                                                <i class="fas fa-file-pdf me-2"></i>File saat ini: 
                                                <a href="{{ asset('storage/' . $ebook->file_url) }}" target="_blank">
                                                    {{ basename($ebook->file_url) }}
                                                </a>
                                                <div class="form-group mt-2">
                                                    <input class="form-check-input" type="checkbox" name="hapus_file" id="hapus_file">
                                                    <label class="form-check-label" for="hapus_file">
                                                        Hapus file saat ini (unggah file baru wajib diisi)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label for="gambar_sampul" class="form-label">Gambar Sampul</label>
                                    <input type="file" class="form-control @error('gambar_sampul') is-invalid @enderror" id="gambar_sampul" name="gambar_sampul" accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG (Maks. 2MB)</small>
                                    @error('gambar_sampul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($ebook->gambar_sampul)
                                        <div id="image-preview" class="mt-2">
                                            <div class="border p-2 rounded" style="max-width: 200px;">
                                                <img src="{{ asset('storage/' . $ebook->gambar_sampul) }}" class="img-fluid" alt="Sampul Saat Ini">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <input class="form-check-input" type="checkbox" id="izin_unduh" name="izin_unduh" value="1" {{ old('izin_unduh', $ebook->izin_unduh) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="izin_unduh">
                                            Izinkan pengunduhan
                                        </label>
                                        <small class="d-block text-muted">Centang untuk mengizinkan pengguna mengunduh ebook ini</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Deskripsi</h6>
                            <div class="form-group">
                                <label for="deskripsi" class="form-label">Deskripsi Ebook</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi ebook">{{ old('deskripsi', $ebook->deskripsi) }}</textarea>
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
                                Simpan Perubahan
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
            if (preview) {
                preview.innerHTML = `
                    <div class="border p-2 rounded" style="max-width: 200px;">
                        <img src="${e.target.result}" class="img-fluid" alt="Preview">
                        <div class="text-center small mt-1">${file.name}</div>
                    </div>
                `;
            } else {
                const div = document.createElement('div');
                div.id = 'image-preview';
                div.innerHTML = `
                    <div class="border p-2 rounded mt-2" style="max-width: 200px;">
                        <img src="${e.target.result}" class="img-fluid" alt="Preview">
                        <div class="text-center small mt-1">${file.name}</div>
                    </div>
                `;
                document.querySelector('label[for="gambar_sampul"]').after(div);
            }
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush