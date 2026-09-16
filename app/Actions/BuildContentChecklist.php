<?php

namespace App\Actions;

use App\Models\About;
use App\Models\Home;
use App\Models\Organization;

class BuildContentChecklist
{
    /**
     * Ringkasan kelengkapan konten inti Kelurahan, ditampilkan di halaman Pengaturan
     * sebagai INFO (bukan validasi blocking) sebelum Admin Kelurahan menekan "Publish Website".
     * Admin tetap bebas publish walau checklist belum lengkap.
     *
     * @return array<int, array{label:string, complete:bool, url:string}>
     */
    public function handle(): array
    {
        $home = Home::first();
        $about = About::first();
        $organization = Organization::first();

        return [
            [
                'label' => 'Beranda (Latar Belakang, Tujuan, atau Output)',
                'complete' => filled($home?->latar_belakang) || filled($home?->tujuan) || filled($home?->output),
                'url' => route('admin.home.edit'),
            ],
            [
                'label' => 'Tentang Kami (Visi/Misi atau Deskripsi)',
                'complete' => filled($about?->visi) || filled($about?->misi) || filled($about?->deskripsi),
                'url' => route('admin.about.edit'),
            ],
            [
                'label' => 'Organisasi (Daftar RT/RW)',
                'complete' => !empty($organization?->daftar_rt),
                'url' => route('admin.organization.edit'),
            ],
        ];
    }
}