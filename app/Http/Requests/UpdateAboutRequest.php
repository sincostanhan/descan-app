<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAboutRequest extends FormRequest
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
            'deskripsi'     => ['required', 'string'],
            'batas_utara'   => ['required', 'string', 'max:255'],
            'batas_barat'   => ['required', 'string', 'max:255'],
            'batas_selatan' => ['required', 'string', 'max:255'],
            'batas_timur'   => ['required', 'string', 'max:255'],
            'visi'          => ['required', 'string', 'max:255'],
            'misi'          => ['required', 'string'],
        ];
    }

    /**
     * Menerjemahkan nama field/kolom agar lebih deskriptif
     */
    public function attributes(): array
    {
        return [
            // Menambahkan kata 'kelurahan' sesuai dengan preferensi yang Anda tulis sebelumnya
            'deskripsi'     => 'Deskripsi kelurahan', 
            'batas_utara'   => 'Batas Utara',
            'batas_barat'   => 'Batas Barat',
            'batas_selatan' => 'Batas Selatan',
            'batas_timur'   => 'Batas Timur',
            'visi'          => 'Visi kelurahan',
            'misi'          => 'Misi kelurahan',
        ];
    }

    /**
     * Menentukan format pesan error-nya secara dinamis
     */
    public function messages(): array
    {
        return [
            // Anda sebelumnya menggunakan 'tidak boleh kosong', kita bisa menerapkannya di sini
            'required' => ':attribute tidak boleh kosong.',
            'string'   => ':attribute harus berupa teks.',
            
            // Format dinamis untuk aturan max
            'max'      => 'Teks :attribute terlalu panjang, maksimal :max karakter.',
        ];
    }
}
