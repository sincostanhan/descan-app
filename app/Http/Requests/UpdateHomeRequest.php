<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeRequest extends FormRequest
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
            'latar_belakang' => ['required', 'string'],
            'tujuan' => ['required', 'string'],
            'output' => ['required', 'string'],
        ];
    }

    /**
     * Menerjemahkan nama field/kolom
     */
    public function attributes(): array
    {
        return [
            'latar_belakang' => 'Latar Belakang',
            'tujuan'         => 'Tujuan',
            'output'         => 'Output',
        ];
    }

    /**
     * Menentukan format pesan error-nya secara dinamis
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',
        ];
    }
}
