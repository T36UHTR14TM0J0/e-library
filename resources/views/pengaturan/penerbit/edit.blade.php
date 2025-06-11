<!-- resources/views/penerbit/edit.blade.php -->
@extends('layouts.app')
@section('title', 'Edit Penerbit')

@section('content')
<div class="container">
    <form action="{{ route('penerbit.update', $penerbit->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Kode Penerbit (readonly) -->
        <div class="form-group mb-3">
            <label class="form-label">Kode Penerbit</label>
            <input type="text" id="kode_penerbit" name="kode_penerbit" class="form-control" 
                   value="{{ old('kode_penerbit', $penerbit->kode_penerbit) }}" readonly>
            <small class="text-muted">Kode penerbit tidak dapat diubah</small>
        </div>

        <!-- Nama Penerbit -->
        <div class="form-group mb-3">
            <label class="form-label">Nama Penerbit</label>
            <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                   value="{{ old('nama', $penerbit->nama) }}">
            @error('nama')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Alamat -->
        <div class="form-group mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea 
                id="alamat" 
                name="alamat" 
                class="form-control @error('alamat') is-invalid @enderror" 
                placeholder="Masukkan alamat"
                rows="4">{{ old('alamat', $penerbit->alamat) }}</textarea>
        </div>

        <!-- Kota -->
        <div class="form-group mb-3">
            <label class="form-label">Kota</label>
            <input type="text" id="kota" name="kota" class="form-control" 
                   value="{{ old('kota', $penerbit->kota) }}">
        </div>

        <!-- Telepon -->
        <div class="form-group mb-3">
            <label class="form-label">Telepon</label>
            <input type="text" id="telepon" name="telepon" class="form-control" 
                   value="{{ old('telepon', $penerbit->telepon) }}">
        </div>

        <!-- Email -->
        <div class="form-group mb-3">
            <label class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="{{ old('email', $penerbit->email) }}">
        </div>

        <!-- Website -->
        <div class="form-group mb-3">
            <label class="form-label">Website</label>
            <input type="text" id="website" name="website" class="form-control" 
                   value="{{ old('website', $penerbit->website) }}">
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('penerbit.index') }}" class="btn btn-sm btn-secondary text-white">Kembali</a>
            <button type="submit" class="btn btn-sm btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection