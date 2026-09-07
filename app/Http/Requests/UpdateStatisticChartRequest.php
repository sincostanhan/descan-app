<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatisticChartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * chart_type boleh dikosongkan — itu sinyal "hapus grafik" (ditangani di UpdateStatisticChart Action),
     * persis seperti perilaku form lama ("-- Kosongkan Jika Ingin Menghapus Grafik --").
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'chart_type' => ['nullable', 'string'],
            'x_axis_column' => ['required_with:chart_type', 'nullable', 'string'],
            'y_axis_columns' => ['required_with:chart_type', 'nullable', 'array', 'min:1'],
            'y_axis_columns.*' => ['string'],
            'y_axis_colors' => ['nullable', 'array'],
            'y_axis_colors.*' => ['nullable', 'string'],
            'has_total_row' => ['nullable'],
            'is_active' => ['nullable'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'has_total_row' => $this->has('has_total_row'),
            'is_active' => $this->has('is_active'),
        ]);
    }

    public function attributes(): array
    {
        return [
            'title' => 'Judul Grafik',
            'chart_type' => 'Tipe Grafik',
            'x_axis_column' => 'Sumbu X',
            'y_axis_columns' => 'Sumbu Y',
        ];
    }
}