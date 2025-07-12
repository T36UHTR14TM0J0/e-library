@extends('layouts.app')
@section('title', 'Edit Data Informasi')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('informasi.update', $informasi->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Judul Informasi <span class="text-danger">*</span></label>
                    <input type="text" id="judul" name="judul" 
                           class="form-control @error('judul') is-invalid @enderror" 
                           placeholder="Masukkan judul fasilitas" value="{{ old('judul', $informasi->judul) }}">
                    @error('judul')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Icon <span class="text-danger">*</span></label>
                    <input type="text" id="icon" name="icon" 
                           class="form-control @error('icon') is-invalid @enderror" 
                           placeholder="Contoh: fas fa-info" value="{{ old('icon', $informasi->icon) }}">
                    @error('icon')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                  <label class="form-label">Warna <span class="text-danger">*</span></label>
                  <select id="warna" name="warna" class="form-select @error('warna') is-invalid @enderror">
                      <option value="">-- Pilih Warna --</option>
                      <option value="secondary" {{ old('warna', $informasi->warna) == 'secondary' ? 'selected' : '' }}>Abu-abu</option>
                      <option value="info" {{ old('warna', $informasi->warna) == 'info' ? 'selected' : '' }}>Biru</option>
                      <option value="success" {{ old('warna', $informasi->warna) == 'success' ? 'selected' : '' }}>Hijau</option>
                      <option value="warning" {{ old('warna', $informasi->warna) == 'warning' ? 'selected' : '' }}>Kuning</option>
                      <option value="danger" {{ old('warna', $informasi->warna) == 'danger' ? 'selected' : '' }}>Merah</option>
                  </select>
                  @error('warna')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
              </div>

                <div class="form-group mb-3">
                    <label class="form-label">Kapasitas Minimum <span class="text-danger">*</span></label>
                    <input type="number" id="min_kapasitas" name="min_kapasitas" 
                           class="form-control @error('min_kapasitas') is-invalid @enderror" 
                           placeholder="Masukkan kapasitas minimum" value="{{ old('min_kapasitas', $informasi->min_kapasitas) }}">
                    @error('min_kapasitas')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Kapasitas Maksimum <span class="text-danger">*</span></label>
                    <input type="number" id="maks_kapasitas" name="maks_kapasitas" 
                           class="form-control @error('maks_kapasitas') is-invalid @enderror" 
                           placeholder="Masukkan kapasitas maksimum" value="{{ old('maks_kapasitas', $informasi->maks_kapasitas) }}">
                    @error('maks_kapasitas')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Waktu Buka <span class="text-danger">*</span></label>
                    <input type="time" id="waktu_buka" name="waktu_buka" 
                           class="form-control @error('waktu_buka') is-invalid @enderror" 
                           value="{{ old('waktu_buka', $informasi->waktu_buka) }}">
                    @error('waktu_buka')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Waktu Tutup <span class="text-danger">*</span></label>
                    <input type="time" id="waktu_tutup" name="waktu_tutup" 
                           class="form-control @error('waktu_tutup') is-invalid @enderror" 
                           value="{{ old('waktu_tutup', $informasi->waktu_tutup) }}">
                    @error('waktu_tutup')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Info Tambahan</label>
                    <input type="text" id="info" name="info" 
                           class="form-control @error('info') is-invalid @enderror" 
                           placeholder="Masukkan info tambahan" value="{{ old('info', $informasi->info) }}">
                    @error('info')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Multiple Input for Facilities -->
        <div class="form-group mb-3">
            <label class="form-label">Fasilitas</label>
            <div id="fasilitas-container">
                @php
                    $oldFasilitas = old('fasilitas', $informasi->fasilitas ? explode(', ', $informasi->fasilitas) : []);
                @endphp
                
                @foreach($oldFasilitas as $index => $informasiItem)
                    <div class="input-group mb-2">
                        <input type="text" name="fasilitas[]" class="form-control @error('fasilitas.'.$index) is-invalid @enderror" 
                               placeholder="Masukkan fasilitas" value="{{ $informasiItem }}">
                        @if($loop->first)
                            <button class="btn btn-success text-white" type="button" onclick="addFasilitasField()">
                                Tambah
                            </button>
                        @else
                            <button class="btn btn-danger text-white" type="button" onclick="removeFasilitasField(this)">
                                Hapus
                            </button>
                        @endif
                        @error('fasilitas.'.$index)
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @endforeach
                
                @if(count($oldFasilitas) === 0)
                    <div class="input-group mb-2">
                        <input type="text" name="fasilitas[]" class="form-control" placeholder="Masukkan fasilitas">
                        <button class="btn btn-success" type="button" onclick="addFasilitasField()">
                            Tambah
                        </button>
                    </div>
                @endif
            </div>
            <small class="text-muted">Tekan tombol tambah untuk menambahkan fasilitas lainnya</small>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('informasi.index') }}" class="btn btn-sm btn-secondary text-white">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-save me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    function addFasilitasField() {
        const container = document.getElementById('fasilitas-container');
        const newInputGroup = document.createElement('div');
        newInputGroup.className = 'input-group mb-2';
        newInputGroup.innerHTML = `
            <input type="text" name="fasilitas[]" class="form-control" placeholder="Masukkan fasilitas">
            <button class="btn btn-danger text-white" type="button" onclick="removeFasilitasField(this)">
                Hapus
            </button>
        `;
        container.appendChild(newInputGroup);
    }

    function removeFasilitasField(button) {
        const inputGroup = button.closest('.input-group');
        inputGroup.remove();
    }
</script>

@endsection