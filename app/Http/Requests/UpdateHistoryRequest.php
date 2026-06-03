<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Ubah input checkbox menjadi boolean
        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'penulis' => ['nullable', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Menerjemahkan nama field/kolom
     */
    public function attributes(): array
    {
        return [
            'penulis'   => 'Penulis',
            'konten'    => 'Konten Sejarah',
            'is_active' => 'Status Aktif',
        ];
    }

    /**
     * Menentukan format pesan error-nya secara dinamis
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',
            'max'      => ':attribute maksimal :max karakter.',
            'boolean'  => 'Pilihan :attribute tidak valid.',
        ];
    }
}
