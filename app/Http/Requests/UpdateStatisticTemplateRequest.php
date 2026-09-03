<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatisticTemplateRequest extends FormRequest
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
            'is_mapped' => ['nullable', 'boolean'],
            'row_headers' => ['required', 'json'],
            'column_headers' => ['required', 'json'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN),
            'is_mapped' => filter_var($this->is_mapped, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function attributes(): array
    {
        return [
            'title' => 'Judul Template',
            'description' => 'Deskripsi',
            'row_headers' => 'Struktur Baris',
            'column_headers' => 'Struktur Kolom',
            'is_mapped' => 'Tampilkan di Dashboard Peta',
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