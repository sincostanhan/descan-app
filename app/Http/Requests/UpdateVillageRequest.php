<?php

namespace App\Http\Requests;

use App\Models\Village;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVillageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // otorisasi sudah dijaga middleware 'role.bps' di route
    }

    public function rules(): array
    {
        // Subdomain WAJIB diisi eksplisit saat edit (beda dengan create yang boleh auto-generate) —
        // supaya perubahan subdomain (yang berdampak ke URL admin Kelurahan) selalu disengaja.
        return [
            'name' => ['required', 'string', 'max:255'],
            'subdomain' => [
                'required', 'string', 'max:255', 'alpha_dash',
                Rule::unique('villages', 'subdomain')->ignore($this->route('village')),   
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