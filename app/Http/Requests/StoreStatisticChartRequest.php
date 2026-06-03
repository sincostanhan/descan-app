<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStatisticChartRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'chart_type' => ['required', 'string'],
            'x_axis_column' => ['required', 'string'],
            'y_axis_columns' => ['required', 'array', 'min:1'],
            'y_axis_columns.*' => ['string'],
            'is_active' => ['nullable'], // Checkbox dari form
        ];
    }

    // Ubah nilai checkbox is_active menjadi boolean (true/false) sebelum divalidasi
    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);
    }

    /**
     * Menerjemahkan nama field/kolom
     */
    public function attributes(): array
    {
        return [
            'title'            => 'Judul Grafik',
            'chart_type'       => 'Jenis Grafik',
            'x_axis_column'    => 'Kolom Sumbu X',
            'y_axis_columns'   => 'Kolom Sumbu Y',
            'y_axis_columns.*' => 'Pilihan Kolom Sumbu Y',
            'is_active'        => 'Status Aktif',
        ];
    }

    /**
     * Menentukan format pesan error-nya
     */
    public function messages(): array
    {
        return [
            // Pesan Umum
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',
            'max'      => ':attribute maksimal :max karakter.',
            'array'    => ':attribute format tidak valid.',
            'boolean'  => 'Pilihan :attribute tidak valid.',

            // Pesan Khusus untuk batas minimal array
            'y_axis_columns.min' => 'Minimal satu :attribute wajib dipilih.',
        ];
    }
}
