<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInfographicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // PDF atau Foto
            'cover_base64' => ['nullable', 'string']
        ];
    }

    /**
     * Menerjemahkan nama field/kolom
     */
    public function attributes(): array
    {
        return [
            'title'        => 'Judul Infografis',
            'description'  => 'Deskripsi',
            'file'         => 'File Infografis',
            'cover_base64' => 'Sampul (Cover)',
        ];
    }

    /**
     * Menentukan format pesan error-nya
     */
    public function messages(): array
    {
        return [
            // Pesan umum untuk input teks
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',
            
            // Pesan spesifik untuk unggahan file
            'file.required' => ':attribute wajib diunggah.',
            'file'          => ':attribute harus berupa file yang valid.',
            'mimes'         => 'Format :attribute hanya boleh: :values.',
            
            // Memisahkan validasi batas (max) agar tidak ambigu
            'title.max' => ':attribute maksimal :max karakter.',
            'file.max'  => 'Ukuran :attribute maksimal :max KB (5 MB).',
        ];
    }
}
