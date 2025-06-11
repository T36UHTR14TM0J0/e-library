<!-- resources/views/kategori/edit.blade.php -->
@extends('layouts.app')
@section('title', 'Edit Kategori')

@section('content')
<form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="nama" class="col-md-4 col-form-label text-md-right">Nama kategori <span class="text-danger">*</span></label>
        <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror"  value="{{ old('nama', $kategori->nama) }}" required>
        @error('nama') <!-- Ganti 'nama' menjadi 'name' -->
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea 
            id="deskripsi" 
            name="deskripsi" 
            class="form-control" 
            placeholder="Masukkan deskripsi"
            rows="4">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('kategori.index') }}" class="btn btn-sm btn-secondary text-white">Kembali</a>
        <button type="submit" class="btn btn-sm btn-primary">Simpan Perubahan</button>
    </div>
</form>

@endsection
