<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublicationRequest extends FormRequest
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
            // 'file' => ['required', 'file', 'mimes:pdf', 'max:5120'] // Maks 5MB, hanya PDF
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'], // Maks 5MB, hanya PDF
            'cover_base64' => ['nullable', 'string']
        ];
    }

    /**
     * Menerjemahkan nama field/kolom
     */
    public function attributes(): array
    {
        return [
            'title'        => 'Judul Publikasi',
            'description'  => 'Deskripsi',
            'file'         => 'Dokumen Publikasi',
            'cover_base64' => 'Sampul (Cover)',
        ];
    }

    /**
     * Menentukan format pesan error-nya secara dinamis
     */
    public function messages(): array
    {
        return [
            // Pesan umum untuk input teks
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',
            
            // Pesan spesifik untuk unggahan file (jika admin memutuskan untuk mengubah dokumen)
            'file'     => ':attribute harus berupa file yang valid.',
            'mimes'    => 'Format :attribute hanya boleh berupa PDF.',
            
            // Pemisahan aturan batas (max)
            'title.max' => ':attribute maksimal :max karakter.',
            'file.max'  => 'Ukuran :attribute maksimal :max KB (5 MB).',
        ];
    }
}
