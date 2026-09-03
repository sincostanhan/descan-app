<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStatisticTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdminBps() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            // Dikirim sebagai string JSON hasil serialize tree header dari builder di frontend
            'row_headers' => ['required', 'json'],
            'column_headers' => ['required', 'json'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function attributes(): array
    {
        return [
            'title' => 'Judul Template',
            'description' => 'Deskripsi',
            'row_headers' => 'Struktur Baris',
            'column_headers' => 'Struktur Kolom',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'json' => ':attribute tidak valid, silakan susun ulang struktur tabel.',
        ];
    }
}