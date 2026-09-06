<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatisticTableEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdminKelurahan() ?? false;
    }

    public function rules(): array
    {
        $template = $this->route('statistic_table_entry')->template;
        $villageId = $this->user()->village_id;

        $rules = [
            'source' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'values' => ['nullable', 'array'],
        ];

        // $cells = $template->cells()->with('columnHeader')->get();
        // $cells = $template->cells()
        //     ->where(fn ($q) => $q->whereNull('village_id')->orWhere('village_id', $villageId))
        //     ->with('columnHeader')->get();
        // whereHas('columnHeader'): abaikan sel "yatim" yang kolomnya sudah dihapus (soft-deleted) BPS —
        // sel semacam itu tidak lagi ditampilkan di form, jadi tidak perlu (dan tidak boleh) divalidasi.
        $cells = $template->cells()
            ->whereHas('columnHeader')
            ->with('columnHeader')
            ->get();

        foreach ($cells as $cell) {
            $rules["values.{$cell->id}"] = match ($cell->columnHeader->data_type) {
                'numeric' => ['nullable', 'numeric'],
                'text' => ['nullable', 'string', 'max:1000'],
                default => ['nullable', 'string', 'max:1000'],
            };
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'values.*.numeric' => 'Kolom ini hanya boleh diisi angka.',
            'values.*.string' => 'Kolom ini hanya boleh diisi teks.',
        ];
    }
}