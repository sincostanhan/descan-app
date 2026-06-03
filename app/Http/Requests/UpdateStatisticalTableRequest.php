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
            'title' => ['required', 'string', 'max:255'],
            'publication' => ['required', 'string', 'max:255'],
            'chapter' => ['required', 'integer'],
            'description' => ['nullable', 'string'],
            'source' => ['nullable', 'string', 'max:255'],
            'columns' => ['required', 'json'], // Data JSON dari tabel
            'content' => ['required', 'json'], // Data JSON isi tabel

            // Validasi untuk konfigurasi grafik (Opsional)
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

    protected function prepareForValidation()
    {
        $mergeData = [];
        // Pastikan checkbox boolean ter-handle dengan baik (true/false)
        if ($this->has('is_chart_active')) {
            $mergeData['is_chart_active'] = filter_var($this->is_chart_active, FILTER_VALIDATE_BOOLEAN);
        } else {
            $mergeData['is_chart_active'] = false; // Set false jika tidak dicentang saat update
        }
        
        if ($this->has('has_total_row')) {
            $mergeData['has_total_row'] = filter_var($this->has_total_row, FILTER_VALIDATE_BOOLEAN);
        } else {
            $mergeData['has_total_row'] = false;
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
            'description'      => 'Deskripsi',
            'source'           => 'Sumber Data',
            'columns'          => 'Struktur Kolom',
            'content'          => 'Isi Data Tabel',
            
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
     * Menentukan format pesan error-nya secara dinamis
     */
    public function messages(): array
    {
        return [
            // Aturan Umum
            'required' => ':attribute wajib diisi.',
            'string'   => ':attribute harus berupa teks.',
            'integer'  => ':attribute harus berupa angka.',
            'max'      => ':attribute maksimal :max karakter.',
            
            // Aturan Khusus Tipe Data
            'json'     => 'Format data pada :attribute tidak valid.',
            'array'    => ':attribute format tidak valid.',
            'boolean'  => 'Pilihan :attribute tidak valid.',
        ];
    }
}
