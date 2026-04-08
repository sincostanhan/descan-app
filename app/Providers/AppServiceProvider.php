<?php

namespace App\Providers;

use App\Observers\GalleryObserver;
use App\Observers\HistoryObserver;
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
    }
}
