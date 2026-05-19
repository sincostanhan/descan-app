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
    
    public function messages(): array
    {
        return [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah.',
            'username.unique' => 'Username sudah digunakan.',
        ];
    }
}
