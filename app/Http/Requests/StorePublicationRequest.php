<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicationRequest extends FormRequest
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
            'file' => ['required', 'file', 'mimes:pdf', 'max:5120'], // Maks 5MB, hanya PDF
            // cover
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
     * Menentukan format pesan error-nya
     */
    public function messages(): array
    {
        return [
            // Pesan umum
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',
            
            // Pesan spesifik file
            'file.required' => ':attribute wajib diunggah.',
            'file'          => ':attribute harus berupa file yang valid.',
            'mimes'         => 'Format :attribute hanya boleh berupa PDF.',
            
            // Pemisahan aturan batas (max)
            'title.max' => ':attribute maksimal :max karakter.',
            'file.max'  => 'Ukuran :attribute maksimal :max KB (5 MB).',
        ];
    }
}
