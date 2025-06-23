@extends('layouts.app')
@section('title', 'Tambah Data User')
@section('content')
<div class="container">
    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                           placeholder="Masukkan nama lengkap" value="{{ old('nama_lengkap') }}">
                    @error('nama_lengkap')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           placeholder="Masukkan email" value="{{ old('email') }}">
                    @error('email')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                           placeholder="Masukkan username" value="{{ old('username') }}">
                    @error('username')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Masukkan password">
                    @error('password')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" 
                           placeholder="Masukkan konfirmasi password">
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    </select>
                    @error('role')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3" id="npm-field">
                    <label class="form-label">NPM</label>
                    <input type="text" name="npm" class="form-control @error('npm') is-invalid @enderror" 
                           placeholder="Masukkan NPM" value="{{ old('npm') }}">
                    @error('npm')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3" id="nidn-field">
                    <label class="form-label">NIDN</label>
                    <input type="text" name="nidn" class="form-control @error('nidn') is-invalid @enderror" 
                           placeholder="Masukkan NIDN" value="{{ old('nidn') }}">
                    @error('nidn')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Program Studi</label>
                    <select name="prodi_id" class="form-control @error('prodi_id') is-invalid @enderror">
                        <option value="" selected>Pilih Prodi (opsional)</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->kode ." - ". $prodi->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('prodi_id')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Status Akun</label>
                    <select name="status_aktif" class="form-control @error('status_aktif') is-invalid @enderror">
                        <option value="1" {{ old('status_aktif', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status_aktif') == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status_aktif')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror">
                    @error('foto')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm text-white">Kembali</a>
            <button type="submit" class="btn btn-sm btn-primary">Simpan Data</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Show/hide NPM/NIDN fields based on role selection
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.querySelector('select[name="role"]');
        const npmField = document.getElementById('npm-field');
        const nidnField = document.getElementById('nidn-field');

        function toggleFields() {
            if (roleSelect.value === 'mahasiswa') {
                npmField.style.display = 'block';
                nidnField.style.display = 'none';
            } else if (roleSelect.value === 'dosen') {
                npmField.style.display = 'none';
                nidnField.style.display = 'block';
            } else {
                npmField.style.display = 'none';
                nidnField.style.display = 'none';
            }
        }

        // Initial toggle
        toggleFields();
        
        // Toggle on change
        roleSelect.addEventListener('change', toggleFields);
    });
</script>
@endpush
@endsection