<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KategoriRequest extends FormRequest
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
            'nama' => 'required',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama kategori wajib diisi',
        ];
    }

    /**
     * Nama atribut kustom untuk pesan error
     */
    public function attributes(): array
    {
        return [
            'nama' => 'Nama kategori',
        ];
    }
}
