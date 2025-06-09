@extends('layouts.app')
@section('title', 'Edit Data User')
@section('content')
<div class="container">
    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                           placeholder="Masukkan nama lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}">
                    @error('nama_lengkap')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           placeholder="Masukkan email" value="{{ old('email', $user->email) }}">
                    @error('email')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                           placeholder="Masukkan username" value="{{ old('username', $user->username) }}">
                    @error('username')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Kosongkan jika tidak ingin mengubah">
                    @error('password')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" 
                           placeholder="Konfirmasi password baru">
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                        <option value="" disabled>Pilih Role</option>
                        <option value="admin" {{ (old('role', $user->role) == 'admin') ? 'selected' : '' }}>Admin</option>
                        <option value="dosen" {{ (old('role', $user->role) == 'dosen') ? 'selected' : '' }}>Dosen</option>
                        <option value="mahasiswa" {{ (old('role', $user->role) == 'mahasiswa') ? 'selected' : '' }}>Mahasiswa</option>
                    </select>
                    @error('role')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3" id="npm-field" style="display: {{ $user->role == 'mahasiswa' ? 'block' : 'none' }};">
                    <label class="form-label">NPM</label>
                    <input type="text" name="npm" class="form-control @error('npm') is-invalid @enderror" 
                           placeholder="Masukkan NPM" value="{{ old('npm', $user->npm) }}">
                    @error('npm')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3" id="nidn-field" style="display: {{ $user->role == 'dosen' ? 'block' : 'none' }};">
                    <label class="form-label">NIDN</label>
                    <input type="text" name="nidn" class="form-control @error('nidn') is-invalid @enderror" 
                           placeholder="Masukkan NIDN" value="{{ old('nidn', $user->nidn) }}">
                    @error('nidn')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Program Studi</label>
                    <select name="prodi_id" class="form-control @error('prodi_id') is-invalid @enderror">
                        <option value="">Pilih Prodi (opsional)</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ (old('prodi_id', $user->prodi_id) == $prodi->id) ? 'selected' : '' }}>
                                {{ $prodi->kode }} - {{ $prodi->nama }}
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
                    <label class="form-label">Foto Profil</label>
                    @if($user->foto)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $user->foto) }}" class="img-thumbnail" width="100">
                        </div>
                    @endif
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
            <a href="{{ route('users.index') }}" class="btn btn-secondary text-white">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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