<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatisticalTableRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'publication' => 'required|string|max:255',
            'chapter' => 'required|integer',
            'description' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'columns' => 'required|json', // Data JSON dari tabel
            'content' => 'required|json', // Data JSON isi tabel
        ];
    }
}
