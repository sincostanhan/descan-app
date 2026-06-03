<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
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
            'username' => [
                'required', 
                'string', 
                'alpha_dash', 
                'max:255', 
                // Gunakan $this->user untuk memanggil model User yang di-binding dari route
                Rule::unique('users')->ignore($this->user)
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
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
            'password'   => 'Password baru', // Diperjelas karena ini form edit
            'village_id' => 'Kelurahan/Desa',
        ];
    }

    /**
     * Menentukan format pesan error-nya secara dinamis
     */
    public function messages(): array
    {
        return [
            // Aturan Umum
            'required'   => ':attribute wajib diisi.',
            'string'     => ':attribute harus berupa teks.',
            'max'        => ':attribute maksimal :max karakter.',
            
            // Aturan Spesifik
            'min'        => ':attribute minimal :min karakter.',
            'confirmed'  => 'Konfirmasi :attribute tidak cocok.',
            'exists'     => ':attribute yang dipilih tidak valid atau tidak terdaftar.',
            
            // Aturan Khusus Username
            'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).',
            'unique'     => ':attribute sudah digunakan. Silakan pilih yang lain.',
        ];
    }
}
