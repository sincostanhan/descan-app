<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreviewStatisticalTableRequest extends FormRequest
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
            'excel_file' => ['required', 'mimes:xlsx,xls,csv', 'max:5120'],
        ];
    }

    /**
     * Menerjemahkan nama field/kolom
     */
    public function attributes(): array
    {
        return [
            'excel_file' => 'File Excel',
        ];
    }

    /**
     * Menentukan format pesan error-nya
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diunggah.',
            
            // :values akan otomatis mencetak 'xlsx, xls, csv'
            'mimes'    => 'Format :attribute harus berupa: :values.', 
            
            // max pada file dihitung dalam satuan Kilobytes (KB). 5120 KB = 5 MB.
            'max'      => 'Ukuran :attribute maksimal :max KB (5 MB).', 
        ];
    }
}
