@extends('layouts.app')
@section('title', 'Tambah Data Prosedur')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('prosedur.store') }}">
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
            <label class="form-label">Judul Prosedur <span class="text-danger">*</span></label>
            <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror" 
                   placeholder="Masukkan judul prosedur" value="{{ old('judul') }}">
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
                      placeholder="Masukkan deskripsi prosedur"
                      rows="4">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('prosedur.index') }}" class="btn btn-sm btn-secondary text-white">
                Kembali
            </a>
            <button type="submit" class="btn btn-sm btn-primary">
                Simpan
            </button>
        </div>
    </form>
</div>

@endsection