<?php

namespace App\Providers;

use App\Observers\GalleryObserver;
use App\Observers\HistoryObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\About::observe(\App\Observers\AboutObserver::class);
        \App\Models\Gallery::observe(GalleryObserver::class);
        \App\Models\History::observe(HistoryObserver::class);
        \App\Models\Organization::observe(\App\Observers\OrganizationObserver::class);
        \App\Models\Publication::observe(\App\Observers\PublicationObserver::class);
        \App\Models\Infographic::observe(\App\Observers\InfographicObserver::class);
        \App\Models\StatisticalTable::observe(\App\Observers\StatisticalTableObserver::class);
        // Gunakan view pagination custom DaisyUI
        Paginator::defaultView('vendor.pagination.daisyui');

        // Bagikan data setting hanya ke komponen nav dan nav-admin
        View::composer(['components.nav', 'components.nav-admin'], function ($view) {
            try {
                $setting = \App\Models\Setting::first();
            } catch (\Exception $e) {
                $setting = null; // Fallback jika tabel belum ada (misal saat proses migrate awal)
            }
            $view->with('globalSetting', $setting);
        });
    }
}
