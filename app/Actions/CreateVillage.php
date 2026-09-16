<?php

namespace App\Actions;

use App\Models\Village;
use Illuminate\Support\Str;

class CreateVillage
{
    /**
     * Provisioning 1 Kelurahan baru dari Admin BPS.
     *
     * SENGAJA cuma buat baris Village — TIDAK perlu pre-create Setting/Organization/About
     * kosong lagi. AboutController, OrganizationController, dan SettingController semuanya
     * sudah toleran terhadap data yang belum ada (pola `Model::first() ?? new Model()` +
     * create-or-update saat disimpan, sama seperti HomeController/HistoryController yang
     * sudah lebih dulu pakai pola ini). Jadi Kelurahan baru otomatis "aman" tanpa perlu
     * baris kosong dibuat di muka.
     */
    public function handle(array $attributes): Village
    {
        return Village::create([
            'name' => $attributes['name'],
            'subdomain' => $attributes['subdomain'] ?? Str::slug($attributes['name']),
        ]);
    }
}