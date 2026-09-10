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

    protected function prepareForValidation(): void
    {
        // Checkbox yang tidak dicentang tidak dikirim browser → default false.
        // Pola sama persis dengan UpdateHistoryRequest::prepareForValidation()
        $this->merge([
            'show_latar_belakang' => $this->has('show_latar_belakang'),
            'show_tujuan'         => $this->has('show_tujuan'),
            'show_output'         => $this->has('show_output'),
            'show_tim'            => $this->has('show_tim'),
        ]);
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
            'tim_kelurahan' => ['nullable', 'string'],
            'show_latar_belakang' => ['boolean'],
            'show_tujuan' => ['boolean'],
            'show_output' => ['boolean'],
            'show_tim' => ['boolean'],
            // 'featured_gallery_photo_id' => ['nullable', 'exists:gallery_photos,id'],
            // 'featured_potensi_wisata_photo_id' => ['nullable', 'exists:potensi_wisata_photos,id'],
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
            'tim_kelurahan'  => 'Tim Kelurahan Cantik',
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
