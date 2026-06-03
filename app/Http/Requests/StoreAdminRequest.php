<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        // Hanya BPS yang boleh mendaftarkan admin
        return $this->user()->hasRole('bps');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'village_id' => ['required', 'exists:villages,id'],
        ];
    }
    
    /**
     * Menerjemahkan nama field/kolom
     */
    public function attributes(): array
    {
        return [
            'name'       => 'Nama Lengkap',
            'username'   => 'Username',
            'password'   => 'Password',
            'village_id' => 'Kelurahan/Desa',
        ];
    }

    /**
     * Menentukan format pesan error-nya
     */
    public function messages(): array
    {
        return [
            'required'   => ':attribute wajib diisi.',
            'string'     => ':attribute harus berupa teks.',
            'max'        => ':attribute maksimal :max karakter.',
            
            // Aturan spesifik
            'min'        => ':attribute minimal :min karakter.',
            'confirmed'  => 'Konfirmasi :attribute tidak cocok.',
            'exists'     => ':attribute yang dipilih tidak valid atau tidak terdaftar.',
            
            // Aturan yang Anda buat sebelumnya, disesuaikan dengan :attribute
            'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_), tanpa spasi.',
            'unique'     => ':attribute ini sudah digunakan. Silakan pilih yang lain.',
        ];
    }
}
