@extends('layouts.app')
@section('title', 'Tambah Data Aktivitas Perpustakaan')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('aktivitas.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group mb-3">
            <label class="form-label">Urutan <span class="text-danger">*</span></label>
            <input type="number" id="urut" name="urut" class="form-control @error('urut') is-invalid @enderror" 
                   placeholder="Masukkan nomor urut" value="{{ old('urut') }}">
            @error('urut')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*">
            <small class="text-muted">Format: JPG, PNG (Maks. 2MB)</small>
            @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div id="image-preview" class="mt-2"></div>
        </div>

        <div class="form-group mb-3">
            <label class="form-label">Judul Aktivitas <span class="text-danger">*</span></label>
            <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror" 
                   placeholder="Masukkan judul aktivitas" value="{{ old('judul') }}">
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
                      rows="4">{{ old('deskripsi') }}</textarea>
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
            <button type="submit" class="btn btn-sm btn-primary">
                Simpan
            </button>
        </div>
    </form>
</div>

@endsection