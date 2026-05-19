<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationRequest extends FormRequest
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
            'lurah' => ['required', 'string', 'max:255'],
            'sekretaris_lurah' => ['required', 'string', 'max:255'],
            'kasi_pemerintahan' => ['nullable', 'string', 'max:255'],
            'kasi_ekonomi' => ['nullable', 'string', 'max:255'],
            'kasi_ketentraman' => ['nullable', 'string', 'max:255'],
            'analis_pembangunan' => ['nullable', 'string', 'max:255'],
            'pranata_barang' => ['nullable', 'string', 'max:255'],
            'pengelola_keamanan' => ['nullable', 'string', 'max:255'],
            'pengadministrasian_umum' => ['nullable', 'string', 'max:255'],
            'pengadministrasian_pemerintahan' => ['nullable', 'string', 'max:255'],
            'pengelola_surat' => ['nullable', 'string', 'max:255'],
            
            // Validasi Array untuk RT dan RW
            'daftar_rw' => ['required', 'array'],
            'daftar_rw.*.rw' => ['required', 'string', 'max:10'],
            'daftar_rw.*.nama' => ['required', 'string', 'max:255'],
            
            'daftar_rt' => ['required', 'array'],
            'daftar_rt.*.rt' => ['required', 'string', 'max:10'],
            'daftar_rt.*.rw' => ['required', 'string', 'max:10'],
            'daftar_rt.*.nama' => ['required', 'string', 'max:255'],
        ];
    }
}
