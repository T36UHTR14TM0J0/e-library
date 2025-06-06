<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdiRequest extends FormRequest
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
        $rules = [
            'kode' => 'required',
            'nama' => 'required',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode prodi wajib diisi',
            'nama.required' => 'Nama prodi wajib diisi',
        ];
    }

    /**
     * Nama atribut kustom untuk pesan error
     */
    public function attributes(): array
    {
        return [
            'kode' => 'Kode prodi',
            'nama' => 'Nama prodi',
        ];
    }
}
