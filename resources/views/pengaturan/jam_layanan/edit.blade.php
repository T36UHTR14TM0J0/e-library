@extends('layouts.app')
@section('title', 'Edit Jam Layanan')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('jam_layanan.update', $jamLayanan->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label class="form-label">Hari <span class="text-danger">*</span></label>
            <select id="hari" name="hari" class="form-select @error('hari') is-invalid @enderror">
                <option value="">Pilih Hari</option>
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                    <option value="{{ $day }}" {{ old('hari', $jamLayanan->hari) == $day ? 'selected' : '' }}>{{ $day }}</option>
                @endforeach
            </select>
            @error('hari')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Waktu Buka <span class="text-danger">*</span></label>
                    <input type="time" id="waktu_buka" name="waktu_buka" 
                           class="form-control @error('waktu_buka') is-invalid @enderror" 
                           value="{{ old('waktu_buka', \Carbon\Carbon::parse($jamLayanan->waktu_buka)->format('H:i')) }}">
                    @error('waktu_buka')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Waktu Tutup <span class="text-danger">*</span></label>
                    <input type="time" id="waktu_tutup" name="waktu_tutup" 
                           class="form-control @error('waktu_tutup') is-invalid @enderror" 
                           value="{{ old('waktu_tutup', \Carbon\Carbon::parse($jamLayanan->waktu_tutup)->format('H:i')) }}">
                    @error('waktu_tutup')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="catatan" class="form-label">Catatan</label>
            <textarea id="catatan" name="catatan" 
                      class="form-control @error('catatan') is-invalid @enderror" 
                      placeholder="Masukkan catatan tambahan (opsional)"
                      rows="3">{{ old('catatan', $jamLayanan->catatan) }}</textarea>
            @error('catatan')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('jam_layanan.index') }}" class="btn btn-sm btn-secondary text-white">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-save me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection