<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenerbitRequest extends FormRequest
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
            'kode_penerbit'      => 'nullable|string|max:50',
            'nama'      => 'required|string|max:100',
            'alamat'    => 'nullable|string',
            'kota'      => 'nullable|string|max:50',
            'telepon'   => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:100',
            'website'   => 'nullable|url|max:100',
        ];
    }

    /**
     * Mendapatkan pesan kesalahan kustom untuk aturan validasi.
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama penerbit wajib diisi',
            'nama.max' => 'Nama penerbit maksimal 100 karakter',
            // 'kota.required' => 'Kota wajib diisi',
            // 'kota.max' => 'Nama kota maksimal 50 karakter',
            // 'telepon.required' => 'Telepon wajib diisi',
            // 'telepon.max' => 'Nomor telepon maksimal 20 karakter',
            // 'email.required' => 'Email wajib diisi',
            // 'email.email' => 'Format email tidak valid',
            // 'email.max' => 'Email maksimal 100 karakter',
            // 'website.required' => 'Website wajib diisi',
            // 'website.url' => 'Format website tidak valid',
            // 'website.max' => 'Alamat website maksimal 100 karakter',
        ];
    }

    /**
     * Mendapatkan nama atribut kustom untuk pesan kesalahan.
     */
    public function attributes(): array
    {
        return [
            'kode_penerbit'      => 'Kode penerbit',
            'nama'      => 'Nama penerbit',
            'alamat'    => 'Alamat',
            'kota'      => 'Kota',
            'telepon'   => 'Telepon',
            'email'     => 'Email',
            'website'   => 'Website',
        ];
    }
}