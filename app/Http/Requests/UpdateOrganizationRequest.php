<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationRequest extends FormRequest
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
            'lurah' => ['required', 'string', 'max:255'],
            'sekretaris_lurah' => 'nullable|string|max:255',
            'kasi_pemerintahan' => 'nullable|string|max:255',
            'kasi_ekonomi' => 'nullable|string|max:255',
            'kasi_ketentraman' => 'nullable|string|max:255',
            'analis_pembangunan' => 'nullable|string|max:255',
            'pranata_barang' => 'nullable|string|max:255',
            'pengelola_keamanan' => 'nullable|string|max:255',
            'pengadministrasian_umum' => 'nullable|string|max:255',
            'pengadministrasian_pemerintahan' => 'nullable|string|max:255',
            'pengelola_surat' => 'nullable|string|max:255',
            // Validasi bahwa ini adalah array (jika diisi)
            'daftar_rw' => 'nullable|array',
            'daftar_rt' => 'nullable|array',
            // Validasi isi di dalam array RW
            'daftar_rw.*.rw'   => 'required_with:daftar_rw|string|max:10',
            'daftar_rw.*.nama' => 'required_with:daftar_rw|string|max:255',
            // Validasi isi di dalam array RT
            'daftar_rt.*.rt'   => 'required_with:daftar_rt|string|max:10',
            'daftar_rt.*.rw'   => 'required_with:daftar_rt|string|max:10',
            'daftar_rt.*.nama' => 'required_with:daftar_rt|string|max:255',
        ];
    }
}
