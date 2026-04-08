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

    public function messages(): array
    {
        return [
            'deskripsi.required' => 'Deskripsi kelurahan tidak boleh kosong.',
            'visi.required'      => 'Visi kelurahan tidak boleh kosong.',
            'visi.max'           => 'Teks Visi terlalu panjang, maksimal 255 karakter.',
        ];
    }
}
