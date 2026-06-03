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

    /**
     * Menerjemahkan nama field/kolom agar lebih ramah dibaca
     */
    public function attributes(): array
    {
        return [
            'lurah' => 'Lurah',
            'sekretaris_lurah' => 'Sekretaris Lurah',
            'kasi_pemerintahan' => 'Kasi Pemerintahan',
            'kasi_ekonomi' => 'Kasi Ekonomi',
            'kasi_ketentraman' => 'Kasi Ketentraman',
            'analis_pembangunan' => 'Analis Pembangunan',
            'pranata_barang' => 'Pranata Barang',
            'pengelola_keamanan' => 'Pengelola Keamanan',
            'pengadministrasian_umum' => 'Pengadministrasian Umum',
            'pengadministrasian_pemerintahan' => 'Pengadministrasian Pemerintahan',
            'pengelola_surat' => 'Pengelola Surat',
            
            // Terjemahan untuk array (menggunakan tanda bintang/wildcard)
            'daftar_rw' => 'Daftar RW',
            'daftar_rw.*.rw' => 'Nomor RW',
            'daftar_rw.*.nama' => 'Nama Ketua RW',
            
            'daftar_rt' => 'Daftar RT',
            'daftar_rt.*.rt' => 'Nomor RT',
            'daftar_rt.*.rw' => 'Nomor RW pada RT terkait',
            'daftar_rt.*.nama' => 'Nama Ketua RT',
        ];
    }

    /**
     * Menentukan format pesan error-nya
     */
    public function messages(): array
    {
        return [
            // :attribute akan otomatis diganti dengan nilai dari method attributes() di atas
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',
            'max'      => ':attribute maksimal :max karakter.',
            'array'    => ':attribute harus berupa daftar (array).',
        ];
    }
}
