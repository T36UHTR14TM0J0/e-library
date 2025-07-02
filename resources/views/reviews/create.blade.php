@extends('layouts.app')
@section('title', 'Tambah Komentar/Ulasan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-chat-square-text me-2"></i> Form Tambah Komentar/Ulasan</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reviews.store') }}">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label class="form-label">Pengguna</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->nama_lengkap }}" readonly>
                            <small class="text-muted">Komentar akan ditautkan ke akun Anda</small>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Rating <span class="text-danger">*</span></label>
                            <select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                <option value="">Pilih Rating</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} Bintang
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Komentar <span class="text-danger">*</span></label>
                            <textarea name="comment" id="comment" rows="5" 
                                class="form-control @error('comment') is-invalid @enderror" 
                                placeholder="Masukkan komentar/ulasan Anda" required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('reviews.index') }}" class="btn btn-sm btn-secondary text-white">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Komentar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection