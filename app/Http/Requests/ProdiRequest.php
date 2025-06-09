<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdiRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan untuk melakukan permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendapatkan aturan validasi yang berlaku untuk permintaan ini.
     */
    public function rules(): array
    {
        return [
            'kode'      => 'required', // Kode prodi harus diisi
            'nama'      => 'required', // Nama prodi harus diisi
            'deskripsi' => 'nullable',  // Deskripsi prodi boleh kosong
        ];
    }

    /**
     * Mendapatkan pesan kesalahan kustom untuk aturan validasi.
     */
    public function messages(): array
    {
        return [
            'kode.required' => 'Kode prodi wajib diisi',
            'nama.required' => 'Nama prodi wajib diisi',
        ];
    }

    /**
     * Mendapatkan nama atribut kustom untuk pesan kesalahan.
     */
    public function attributes(): array
    {
        return [
            'kode'      => 'Kode prodi',
            'nama'      => 'Nama prodi',
            'deskripsi' => 'Deskripsi prodi',
        ];
    }
}
