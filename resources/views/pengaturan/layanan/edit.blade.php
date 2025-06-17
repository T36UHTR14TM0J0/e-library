@extends('layouts.app')
@section('title', 'Edit Data Layanan')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('layanan.update', $layanan->id) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-3">
            <label class="form-label">Nama Layanan <span class="text-danger">*</span></label>
            <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                   placeholder="Masukkan nama layanan" value="{{ old('nama', $layanan->nama) }}">
            @error('nama')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label class="form-label">Icon (Font Awesome) <span class="text-danger">*</span></label>
                <input type="text" id="icon" name="icon" class="form-control @error('icon') is-invalid @enderror" 
                       placeholder="Contoh: fas fa-home" value="{{ old('icon', $layanan->icon) }}">
            <small class="text-muted">Gunakan class icon dari Font Awesome (contoh: fas fa-home)</small>
            @error('icon')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
            <textarea id="deskripsi" name="deskripsi" 
                      class="form-control @error('deskripsi') is-invalid @enderror" 
                      placeholder="Masukkan deskripsi layanan"
                      rows="4">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
            @error('deskripsi')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('layanan.index') }}" class="btn btn-sm btn-secondary text-white">
               Kembali
            </a>
            <button type="submit" class="btn btn-sm btn-primary">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection