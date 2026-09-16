<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVillageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // otorisasi sudah dijaga middleware 'role.bps' di route
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            // Nullable: kalau kosong, CreateVillage Action generate otomatis dari 'name' (Str::slug).
            'subdomain' => ['nullable', 'string', 'max:255', 'alpha_dash', 'unique:villages,subdomain'],
        ];
    }

    public function messages(): array
    {
        return [
            'subdomain.alpha_dash' => 'Subdomain hanya boleh berisi huruf, angka, strip, dan garis bawah.',
            'subdomain.unique' => 'Subdomain ini sudah dipakai Kelurahan lain.',
        ];
    }
}