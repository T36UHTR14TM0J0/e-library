<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $user = $this->user();

        return [
            'nama_lengkap' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'username' => 'required|max:50|unique:users,username,'.$user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::min(8)
                          ->letters()
                          ->mixedCase()
                          ->numbers()
                          ->symbols()],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_foto' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah digunakan',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'foto.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}