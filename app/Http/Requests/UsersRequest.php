<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'nama_lengkap'  => 'required|max:255',
            'email'         => 'required|email|unique:users,email,'.$userId,
            'username'      => 'required|max:50|unique:users,username,'.$userId,
            'password'      => [$this->isMethod('post') ? 'required' : 'nullable', 
                              'confirmed', 
                              Rules\Password::min(8)
                                  ->letters()
                                  ->mixedCase()
                                  ->numbers()
                                  ->symbols()],
            'role'          => 'required|in:admin,dosen,mahasiswa',
            'npm'           => 'nullable|required_if:role,mahasiswa|unique:users,npm,'.$userId,
            'nidn'          => 'nullable|required_if:role,dosen|unique:users,nidn,'.$userId,
            'prodi_id'      => 'nullable|exists:prodis,id',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_foto'   => 'nullable|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->password === null) {
            $this->request->remove('password');
        }
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'email.required'        => 'Email harus diisi',
            'email.email'           => 'Format email tidak valid',
            'email.unique'          => 'Email sudah terdaftar',
            'username.required'     => 'Username harus diisi',
            'username.unique'       => 'Username sudah digunakan',
            'password.required'     => 'Password harus diisi (minimal 8 karakter dengan kombinasi huruf besar, kecil, angka, dan simbol)',
            'password.confirmed'    => 'Konfirmasi password tidak cocok',
            'role.required'         => 'Pilih role pengguna',
            'role.in'               => 'Role tidak valid',
            'npm.required_if'       => 'NPM wajib diisi untuk mahasiswa',
            'npm.unique'            => 'NPM sudah terdaftar',
            'nidn.required_if'      => 'NIDN wajib diisi untuk dosen',
            'nidn.unique'           => 'NIDN sudah terdaftar',
            'prodi_id.exists'       => 'Program studi tidak valid',
            'foto.image'           => 'File harus berupa gambar',
            'foto.mimes'            => 'Format gambar harus jpeg, png, jpg, atau gif',
            'foto.max'              => 'Ukuran gambar maksimal 2MB',
        ];
    }
}