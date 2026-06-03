<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryRequest extends FormRequest
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
            // 'nama_kegiatan' => ['required', 'string', 'max:255'],
            'judul' => ['required', 'string', 'max:255'],
            // 'nama_kegiatan' => ['required', 'string', 'max:255'],
            'photos' => ['required', 'array'], // Pastikan input foto berupa array
            'photos.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'] // Validasi per-file max 2MB
        ];
    }

    /**
     * Menerjemahkan nama field/kolom
     */
    public function attributes(): array
    {
        return [
            'judul'    => 'Judul',
            'photos'   => 'Daftar Foto',
            'photos.*' => 'Foto',
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
            'array'    => ':attribute format tidak valid.',
            
            // Pesan spesifik untuk array file gambar
            'photos.required' => 'Minimal satu foto wajib diunggah.',
            'image'           => ':attribute harus berupa file gambar yang valid.',
            'mimes'           => 'Format :attribute hanya boleh: :values.',
            
            // Memisahkan pesan 'max' antara string (judul) dan file (foto)
            'judul.max'    => ':attribute maksimal :max karakter.',
            'photos.*.max' => 'Ukuran :attribute maksimal :max KB (2 MB).',
        ];
    }
}
