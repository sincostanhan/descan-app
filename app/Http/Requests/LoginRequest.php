<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Menerjemahkan nama field/kolom
     */
    public function attributes(): array
    {
        return [
            // Anda bisa menggunakan 'Nama Pengguna' dan 'Kata Sandi' 
            // jika ingin menggunakan bahasa Indonesia yang lebih baku.
            'username' => 'Username', 
            'password' => 'Password',
        ];
    }

    /**
     * Menentukan format pesan error
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute format tidak valid.', // Disesuaikan agar lebih natural untuk form login
        ];
    }
}
