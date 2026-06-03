<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }

    /**
     * Menerjemahkan nama field/kolom
     */
    public function attributes(): array
    {
        return [
            'name'  => 'Nama Lengkap',
            'email' => 'Alamat Email',
        ];
    }

    /**
     * Menentukan format pesan error-nya
     */
    public function messages(): array
    {
        return [
            'required'  => ':attribute wajib diisi.',
            'string'    => ':attribute harus berupa teks.',
            'max'       => ':attribute maksimal :max karakter.',
            
            // Pesan untuk rule spesifik email
            'lowercase' => ':attribute harus menggunakan huruf kecil semua.',
            'email'     => 'Format :attribute tidak valid.',
            'unique'    => ':attribute ini sudah digunakan oleh pengguna lain. Silakan gunakan yang berbeda.',
        ];
    }
}
