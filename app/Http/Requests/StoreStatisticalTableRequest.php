<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStatisticalTableRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'publication' => ['required', 'string', 'max:255'],
            'chapter' => ['required', 'string', 'max:255'],
            'columns' => ['required', 'json'],
            'content' => ['required', 'json'],
            'description' => ['nullable', 'string'],
            'source' => ['nullable', 'string', 'max:255'],

            // Validasi untuk konfigurasi grafik (dibuat nullable/opsional 
            // agar admin tidak wajib membuat grafik jika tidak mau)
            'chart_title' => ['nullable', 'string', 'max:255'],
            'chart_type' => ['nullable', 'string'],
            'x_axis_column' => ['nullable', 'string'],
            'y_axis_columns' => ['nullable', 'array'],
            'y_axis_columns.*' => ['string'],
            'y_axis_colors' => ['nullable', 'array'],
            'y_axis_colors.*' => ['string', 'max:7'],
            'has_total_row' => ['nullable', 'boolean'],
            'is_chart_active' => ['nullable', 'boolean'],
        ];
    }

    //method prepareForValidation untuk menangani checkbox
    protected function prepareForValidation()
    {
        $mergeData = [];
        if ($this->has('is_chart_active')) {
            $mergeData['is_chart_active'] = filter_var($this->is_chart_active, FILTER_VALIDATE_BOOLEAN);
        }
        if ($this->has('has_total_row')) {
            $mergeData['has_total_row'] = filter_var($this->has_total_row, FILTER_VALIDATE_BOOLEAN);
        }
        
        if(!empty($mergeData)) {
            $this->merge($mergeData);
        }
    }

    /**
     * Menerjemahkan nama field/kolom
     */
    public function attributes(): array
    {
        return [
            'title'            => 'Judul Tabel',
            'publication'      => 'Publikasi',
            'chapter'          => 'Bab/Kategori',
            'columns'          => 'Struktur Kolom',
            'content'          => 'Isi Data Tabel',
            'description'      => 'Deskripsi',
            'source'           => 'Sumber Data',
            
            // Konfigurasi Grafik
            'chart_title'      => 'Judul Grafik',
            'chart_type'       => 'Jenis Grafik',
            'x_axis_column'    => 'Kolom Sumbu X',
            'y_axis_columns'   => 'Kolom Sumbu Y',
            'y_axis_columns.*' => 'Pilihan Kolom Sumbu Y',
            'y_axis_colors'    => 'Warna Sumbu Y',
            'y_axis_colors.*'  => 'Kode Warna',
            'has_total_row'    => 'Baris Total',
            'is_chart_active'  => 'Status Aktif Grafik',
        ];
    }

    /**
     * Menentukan format pesan error-nya
     */
    public function messages(): array
    {
        return [
            // Aturan Umum
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',
            'max'      => ':attribute maksimal :max karakter.',
            
            // Aturan Khusus Tipe Data
            'json'     => 'Format data pada :attribute tidak valid.',
            'array'    => ':attribute format tidak valid.',
            'boolean'  => 'Pilihan :attribute tidak valid.',
        ];
    }
}
