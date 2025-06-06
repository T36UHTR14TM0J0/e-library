@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Profil</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <div id="previewFoto">
                                    @if($user->foto)
                                        <img src="{{ asset('storage/' . $user->foto) }}" 
                                            class="img-thumbnail rounded-circle mb-3" 
                                            width="200" height="200" alt="Foto Profil">
                                    @else
                                        <img src="{{ asset('assets/images/profile_default.png') }}" 
                                            class="img-thumbnail rounded-circle mb-3" 
                                            width="200" height="200" alt="Foto Profil">
                                    @endif
                                </div>
                                <input type="file" name="foto" id="foto" class="form-control mt-2 d-none" accept="image/*">
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="document.getElementById('foto').click()">
                                    <i class="icon-camera"></i> Pilih Foto
                                </button>
                                @error('foto')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-8">
                              @if($user->role === 'mahasiswa')
                                <div class="form-group mb-3">
                                    <label class="form-label">NPM <span class="text-danger">*</span></label>
                                    <input type="text" name="npm" 
                                           class="form-control"
                                           value="{{ old('npm', $user->npm) }}" readonly>
                                </div>
                                @endif

                                @if($user->role === 'dosen')
                                <div class="form-group mb-3">
                                    <label class="form-label">NIDN <span class="text-danger">*</span></label>
                                    <input type="text" name="nidn" 
                                           class="form-control" 
                                           value="{{ old('nidn', $user->nidn) }}" 
                                           readonly>
                                </div>
                                @endif
                                <div class="form-group mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_lengkap" 
                                           class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                           value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                                    @error('nama_lengkap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" 
                                           class="form-control @error('username') is-invalid @enderror" 
                                           value="{{ old('username', $user->username) }}" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                

                                @if($user->prodi_id)
                                <div class="form-group mb-3">
                                    <label class="form-label">Program Studi</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $user->prodi->nama_prodi ?? 'Tidak diketahui' }}" readonly>
                                </div>
                                @endif

                                <div class="form-group mb-3">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" name="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Kosongkan jika tidak ingin mengubah">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" 
                                           class="form-control" 
                                           placeholder="Konfirmasi password baru">
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('profile.show') }}" class="btn btn-secondary text-white">
                                        Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                       Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview foto sebelum upload
    document.getElementById('foto').addEventListener('change', function(e) {
        const preview = document.getElementById('previewFoto');
        const file = e.target.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail rounded-circle" width="200" height="200" alt="Preview Foto">`;
        }
        
        if (file) {
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush