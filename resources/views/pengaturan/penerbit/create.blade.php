<!-- resources/views/penerbit/create.blade.php -->
@extends('layouts.app') <!-- Pastikan ini sesuai dengan struktur folder Anda -->
@section('title', 'Tambah Data penerbit')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('penerbit.store') }}">
        @csrf
        <div class="form-group mb-3">
            <label class="form-label">Kode Penerbit</label>
            <input type="text" id="kode_penerbit" name="kode_penerbit" class="form-control" 
                value="{{ $kode ?? '' }}" readonly>
            <small class="text-muted">Kode penerbit akan digenerate otomatis</small>
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Nama Penerbit <span class="text-danger">*</span></label>
            <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama penerbit" value="{{ old('nama') }}">
            @error('nama')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="form-group mb-3">
          <label for="alamat" class="form-label">Alamat</label>
          <textarea 
              id="alamat" 
              name="alamat" 
              class="form-control" 
              placeholder="Masukkan alamat"
              rows="4">{{ old('alamat') }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label class="form-label">Kota</label>
            <input type="text" id="kota" name="kota" class="form-control" placeholder="Masukkan kota penerbit" value="{{ old('kota') }}">
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Telepon</label>
            <input type="text" id="telepon" name="telepon" class="form-control" placeholder="Masukkan telepon penerbit" value="{{ old('telepon') }}">
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email penerbit" value="{{ old('email') }}">
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Website</label>
            <input type="text" id="website" name="website" class="form-control" placeholder="Masukkan website penerbit" value="{{ old('website') }}">
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('penerbit.index') }}" class="btn btn-sm btn-secondary text-white">Kembali</a>
            <button type="submit" class="btn btn-sm btn-primary">Simpan Data</button>
        </div>
    </form>
</div>
@endsection
