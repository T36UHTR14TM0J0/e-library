<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EbookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'kategori_id'   => 'required|exists:kategoris,id',
            'prodi_id'      => 'nullable|exists:prodis,id',
            'penerbit_id'   => 'nullable',
            'deskripsi'     => 'nullable|string',
            'izin_unduh'    => 'nullable|string',
            'file_url'      => 'required|file|mimes:pdf,epub|max:10240', // 10MB
            'gambar_sampul' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Untuk update, file_url tidak harus selalu diisi
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['file_url'] = 'nullable|file|mimes:pdf,epub|max:10240';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'judul.required'        => 'Judul ebook wajib diisi',
            'judul.max'             => 'Judul ebook maksimal 255 karakter',
            'penulis.required'      => 'Nama penulis wajib diisi',
            'penulis.max'           => 'Nama penulis maksimal 255 karakter',
            'kategori_id.required'  => 'Kategori wajib dipilih',
            'kategori_id.exists'    => 'Kategori yang dipilih tidak valid',
            'file_url.required'     => 'File ebook wajib diupload',
            'file_url.file'         => 'File harus berupa dokumen',
            'file_url.mimes'        => 'Format file yang diperbolehkan: PDF, EPUB',
            'file_url.max'          => 'Ukuran file maksimal 10MB',
        ];
    }

    public function attributes()
    {
        return [
            'judul'         => 'Judul Ebook',
            'penulis'       => 'Penulis',
            'kategori_id'   => 'Kategori',
            'penerbit_id'   => 'Penerbit',
            'prodi_id'      => 'Program Studi',
            'deskripsi'     => 'Deskripsi',
            'izin_unduh'    => 'Izin Unduh',
            'file_url'      => 'File Ebook',
            'gambar_sampul' => 'Gambar Sampul',
            
        ];
    }
}