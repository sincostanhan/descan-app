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
            'title' => ['nullable', 'string', 'max:255'], // ganti dari required, konsisten dengan Update
            'chart_type' => ['nullable', 'string'],        // ganti dari required — boleh kosong (grafik kategori-saja)
            'x_axis_column' => ['required_with:chart_type', 'nullable', 'string'],   // ganti
            'y_axis_columns' => ['required_with:chart_type', 'nullable', 'array'],   // ganti (hapus min:1)
            'y_axis_columns.*' => ['string'],
            'y_axis_colors' => ['nullable', 'array'],
            'y_axis_colors.*' => ['nullable', 'string'],
            'included_rows' => ['nullable', 'array'],
            'included_rows.*' => ['integer'],
            'is_active' => ['nullable'],
            'category_columns' => ['nullable', 'array'],        // BARU
            'category_columns.*' => ['string'],                  // BARU
            'category_chart_types' => ['nullable', 'array'],      // BARU
            'category_chart_types.*' => ['in:pie,bar'],           // BARU
        ];
    }

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
