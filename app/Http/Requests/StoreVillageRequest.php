<?php

namespace App\Http\Requests;

use App\Models\Village;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'subdomain' => ['nullable', 'string', 'max:255', 'alpha_dash', 'unique:villages,subdomain' ,   
            Rule::notIn(Village::RESERVED_SUBDOMAINS),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'subdomain.alpha_dash' => 'Subdomain hanya boleh berisi huruf, angka, strip, dan garis bawah.',
            'subdomain.unique' => 'Subdomain ini sudah dipakai Kelurahan lain.',
            'subdomain.not_in' => 'Nama subdomain ini dipakai sistem dan tidak boleh digunakan.',
        ];
    }
}