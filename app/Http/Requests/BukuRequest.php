<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BukuRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:bukus,isbn',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:'.date('Y'),
            'jumlah' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'prodi_id' => 'nullable|exists:prodis,id',
            'gambar_sampul' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Untuk update, kita perlu mengecualikan ISBN buku saat ini dari validasi unique
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['isbn'] = 'nullable|string|max:20|unique:bukus,isbn,'.$this->buku->id;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'judul.required' => 'Judul buku wajib diisi',
            'judul.max' => 'Judul buku maksimal 255 karakter',
            'penulis.required' => 'Nama penulis wajib diisi',
            'penulis.max' => 'Nama penulis maksimal 255 karakter',
            'isbn.max' => 'ISBN maksimal 20 karakter',
            'isbn.unique' => 'ISBN sudah digunakan oleh buku lain',
            'penerbit.required' => 'Nama penerbit wajib diisi',
            'penerbit.max' => 'Nama penerbit maksimal 255 karakter',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka',
            'tahun_terbit.min' => 'Tahun terbit minimal 1900',
            'tahun_terbit.max' => 'Tahun terbit tidak boleh lebih dari tahun sekarang',
            'jumlah.required' => 'Jumlah buku wajib diisi',
            'jumlah.integer' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal 1 buku',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid',
            'prodi_id.exists' => 'Program studi yang dipilih tidak valid',
            'gambar_sampul.image' => 'File harus berupa gambar',
            'gambar_sampul.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg, gif',
            'gambar_sampul.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }

    public function attributes()
    {
        return [
            'judul' => 'Judul Buku',
            'penulis' => 'Penulis',
            'isbn' => 'ISBN',
            'penerbit' => 'Penerbit',
            'tahun_terbit' => 'Tahun Terbit',
            'jumlah' => 'Jumlah',
            'deskripsi' => 'Deskripsi',
            'kategori_id' => 'Kategori',
            'prodi_id' => 'Program Studi',
            'gambar_sampul' => 'Gambar Sampul',
        ];
    }
}