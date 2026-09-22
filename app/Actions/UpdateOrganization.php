<?php
namespace App\Actions;

use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class UpdateOrganization
{
    public function handle(array $validatedData): Organization
    {
        return DB::transaction(function () use ($validatedData) {
            $positions = $validatedData['positions'] ?? [];
            unset($validatedData['positions']); // guarded=['id'], kolom ini gak ada di tabel organizations

            $organization = Organization::first();
            $organization = $organization
                ? tap($organization)->update($validatedData)
                : Organization::create($validatedData);

            // Full-replace — konsisten dengan pola daftar_rt/daftar_rw yang sudah ada,
            // lebih simpel dari diffing per baris, dan volumenya kecil (~10-15 baris).
            $organization->positions()->delete();

            foreach ($positions as $order => $position) {
                if (blank($position['label'] ?? null)) {
                    continue; // baris kosong dari repeater diabaikan
                }

                $organization->positions()->create([
                    'level' => $position['level'],
                    'label' => $position['label'],
                    'name'  => $position['name'] ?? null,
                    'order' => $order,
                ]);
            }

            return $organization;
        });
    }
}