<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAboutRequest extends FormRequest
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
        return [
            'deskripsi' => ['required', 'string'],
            'batas_utara' => ['required', 'string', 'max:255'],
            'batas_barat' => ['required', 'string', 'max:255'],
            'batas_selatan' => ['required', 'string', 'max:255'],
            'batas_timur' => ['required', 'string', 'max:255'],
            'visi' => ['required', 'string', 'max:255'],
            'misi' => ['required', 'string'],
        ];
    }

    /**
     * Menerjemahkan nama field/kolom agar lebih ramah dibaca
     */
    public function attributes(): array
    {
        return [
            'deskripsi' => 'Deskripsi',
            'batas_utara' => 'Batas Utara',
            'batas_barat' => 'Batas Barat',
            'batas_selatan' => 'Batas Selatan',
            'batas_timur' => 'Batas Timur',
            'visi' => 'Visi',
            'misi' => 'Misi',
        ];
    }

    /**
     * Menentukan format pesan error-nya
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',
            'max'      => ':attribute maksimal :max karakter.',
        ];
    }
}
