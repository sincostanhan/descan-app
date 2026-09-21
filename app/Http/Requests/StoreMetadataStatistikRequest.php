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
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // PDF atau Foto
            'cover_base64' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title'        => 'Judul Metadata',
            'file'         => 'File Metadata',
            'cover_base64' => 'Sampul (Cover)',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',

            'file.required' => ':attribute wajib diunggah.',
            'file'          => ':attribute harus berupa file yang valid.',
            'mimes'         => 'Format :attribute hanya boleh: :values.',

            'title.max' => ':attribute maksimal :max karakter.',
            'file.max'  => 'Ukuran :attribute maksimal :max KB (5 MB).',
        ];
    }
}