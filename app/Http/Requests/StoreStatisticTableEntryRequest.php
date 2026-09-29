<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStatisticTableEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdminKelurahan() ?? false;
    }

    /**
     * Rules dibangun DINAMIS dari data_type tiap kolom template —
     * inilah "Fitur Validasi Ketat" yang dikunci Admin BPS saat membuat template.
     */
    public function rules(): array
    {
        $template = $this->route('statistic_template');
        $villageId = $this->user()->village_id;

        $rules = [
            'publication_id' => [
                'nullable', 'integer',
                Rule::exists('publications', 'id')->where('village_id', $villageId),
            ],
            'source' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'values' => ['nullable', 'array'],
        ];

        // $cells = $template->cells()->with('columnHeader')->get();
        // $cells = $template->cells()
        //     ->where(fn ($q) => $q->whereNull('village_id')->orWhere('village_id', $villageId))
        //     ->with('columnHeader')->get();
        $cells = $template->cells()
            ->whereHas('columnHeader')
            ->with('columnHeader')
            ->get();

        foreach ($cells as $cell) {
            $rules["values.{$cell->id}"] = match ($cell->columnHeader->data_type) {
                'numeric' => ['nullable', 'numeric'],
                // 'text' => ['nullable', 'string', 'max:1000'],
                // default => ['nullable', 'string', 'max:1000'], // 'both' = bebas angka/teks
                'text' => ['nullable', 'string', 'max:100'],
                default => ['nullable', 'string', 'max:100'],
            };
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'publication_id.exists' => 'Publikasi yang dipilih tidak valid.',
            'values.*.numeric' => 'Kolom ini hanya boleh diisi angka.',
            'values.*.string' => 'Kolom ini hanya boleh diisi teks.',
        ];
    }
}