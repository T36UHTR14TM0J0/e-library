@extends('layouts.app')
@section('title', 'Edit Data Aktivitas Perpustakaan')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('aktivitas.update', $aktivita->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-3">
            <label class="form-label">Urutan <span class="text-danger">*</span></label>
            <input type="number" id="urut" name="urut" class="form-control @error('urut') is-invalid @enderror" 
                   placeholder="Masukkan nomor urut" value="{{ old('urut', $aktivita->urut) }}" min="1">
            @error('urut')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="foto" class="form-label">Foto</label>
            
            @if($aktivita->foto)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $aktivita->foto) }}" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                </div>
            @endif
            
            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg,image/gif">
            <small class="text-muted">Format: JPG, PNG, GIF (Maks. 2MB)</small>
            @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div id="image-preview" class="mt-2"></div>
        </div>

        <div class="form-group mb-3">
            <label class="form-label">Judul Aktivitas <span class="text-danger">*</span></label>
            <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror" 
                   placeholder="Masukkan judul aktivitas" value="{{ old('judul', $aktivita->judul) }}">
            @error('judul')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
            <textarea id="deskripsi" name="deskripsi" 
                      class="form-control @error('deskripsi') is-invalid @enderror" 
                      placeholder="Masukkan deskripsi galleri"
                      rows="4">{{ old('deskripsi', $aktivita->deskripsi) }}</textarea>
            @error('deskripsi')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('aktivitas.index') }}" class="btn btn-sm btn-secondary text-white">
                Kembali
            </a>
            <div>
                <button type="submit" class="btn btn-sm btn-primary">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Image preview functionality
document.getElementById('foto').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxHeight = '200px';
            img.classList.add('img-thumbnail');
            preview.appendChild(img);
        }
        
        reader.readAsDataURL(this.files[0]);
    }
});
</script>

@endsection