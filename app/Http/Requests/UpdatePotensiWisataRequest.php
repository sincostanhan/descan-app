<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePotensiWisataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:situs_bersejarah,umum'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'Nama',
            'kategori' => 'Kategori',
            'photos' => 'Daftar Foto',
            'photos.*' => 'Foto',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'in' => ':attribute yang dipilih tidak valid.',
            'image' => ':attribute harus berupa file gambar yang valid.',
            'mimes' => 'Format :attribute hanya boleh: :values.',
            'photos.*.max' => 'Ukuran :attribute maksimal :max KB (2 MB).',
        ];
    }
}