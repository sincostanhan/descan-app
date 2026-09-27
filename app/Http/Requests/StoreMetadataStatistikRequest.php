<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMetadataStatistikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'tahun' => ['required', 'integer', 'digits:4', 'between:1900,' . (now()->year + 1)],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // PDF atau Foto
            'cover_base64' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title'        => 'Judul Metadata',
            'tahun'        => 'Tahun',
            'file'         => 'File Metadata',
            'cover_base64' => 'Sampul (Cover)',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',

            'tahun.integer' => ':attribute harus berupa angka.',
            'tahun.digits'  => ':attribute harus 4 digit, contoh: 2025.',
            'tahun.between' => ':attribute harus antara :min dan :max.',

            'file.required' => ':attribute wajib diunggah.',
            'file'          => ':attribute harus berupa file yang valid.',
            'mimes'         => 'Format :attribute hanya boleh: :values.',

            'title.max' => ':attribute maksimal :max karakter.',
            'file.max'  => 'Ukuran :attribute maksimal :max KB (5 MB).',
        ];
    }
}