<!-- resources/views/prodi/create.blade.php -->
@extends('layouts.app') <!-- Pastikan ini sesuai dengan struktur folder Anda -->
@section('title', 'Tambah Data Prodi')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('prodi.store') }}">
        @csrf
        <div class="form-group mb-3">
            <label class="form-label">Kode Prodi <span class="text-danger">*</span></label>
            <input type="text" id="kode" name="kode" class="form-control @error('kode') is-invalid @enderror" placeholder="Masukkan kode prodi" value="{{ old('kode') }}">
            @error('kode')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Nama prodi <span class="text-danger">*</span></label>
            <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama prodi" value="{{ old('nama') }}">
            @error('nama')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="form-group mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea 
            id="deskripsi" 
            name="deskripsi" 
            class="form-control" 
            placeholder="Masukkan deskripsi"
            rows="4">{{ old('deskripsi') }}</textarea>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('prodi.index') }}" class="btn btn-sm btn-secondary text-white">Kembali</a>
        <button type="submit" class="btn btn-sm btn-primary">Simpan Data</button>
    </div>
    </form>
</div>
@endsection
