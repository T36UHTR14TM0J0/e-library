@extends('layouts.app')
@section('title', 'Edit Peraturan')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('peraturan.update', $peraturan->id) }}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Urutan <span class="text-danger">*</span></label>
                    <input type="number" id="urut" name="urut" 
                           class="form-control @error('urut') is-invalid @enderror" 
                           value="{{ old('urut', $peraturan->urut) }}">
                    @error('urut')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Tipe Peraturan <span class="text-danger">*</span></label>
                    <select id="tipe" name="tipe" class="form-select @error('tipe') is-invalid @enderror">
                        <option value="1" {{ old('tipe', $peraturan->tipe) == '1' ? 'selected' : '' }}>Peraturan Umum</option>
                        <option value="2" {{ old('tipe', $peraturan->tipe) == '2' ? 'selected' : '' }}>Peraturan Ruang Baca</option>
                    </select>
                    @error('tipe')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label class="form-label">Isi Peraturan <span class="text-danger">*</span></label>
            <textarea id="text" name="text" rows="3"
                      class="form-control @error('text') is-invalid @enderror">{{ old('text', $peraturan->text) }}</textarea>
            @error('text')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('peraturan.index') }}" class="btn btn-secondary text-white">
                Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection