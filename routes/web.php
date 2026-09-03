<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SetupController;
use App\Http\Controllers\AdminBps\StatisticTemplateController;
use App\Http\Controllers\AdminBps\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfographicController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\PublicStatisticController;
use App\Http\Controllers\StatisticalTableController;
use App\Http\Controllers\StatisticChartController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::domain(env('APP_URL_BASE', 'descan.scthan.tech'))->group(function () {
    // untuk melempar user kembali ke halaman login setelah logout
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate')
            ->middleware('throttle:5,1');
    });

    Route::middleware(['auth'])->group(function () {
        Route::prefix('admin-bps')->name('admin-bps.')->middleware(['role.bps'])->group(function () {
            // CRUD Admin Kelurahan
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

            // CRUD Template Tabel Statistik
            Route::resource('statistic-templates', StatisticTemplateController::class)->except(['show']);
        });
    });
});

// Rute untuk spesifik kelurahan (baadia.descan.scthan.tech)
Route::domain('{subdomain}.' . env('APP_URL_BASE', 'descan.scthan.tech'))->group(function () {

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/tentang-kami', [AboutController::class, 'index'])->name('about.index');
Route::get('/sejarah', [HistoryController::class, 'index'])->name('history.index');
Route::get('/organisasi', [OrganizationController::class, 'index'])->name('organization.index');
Route::get('/galeri', [GalleryController::class, 'indexPublik'])->name('gallery.index');
Route::get('/publikasi', [PublicationController::class, 'indexPublic'])->name('publication.index');
Route::get('/infografis', [InfographicController::class, 'indexPublic'])->name('infographic.index');
Route::get('/statistik', [PublicStatisticController::class, 'index'])->name('public.statistic.index');
Route::get('/statistik/{statistic}', [PublicStatisticController::class, 'show'])->name('public.statistic.show');

// Route::middleware('guest')->group(function () {
//     Route::get('/login', [AuthController::class, 'login'])->name('login');
//     Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
// });

Route::middleware(['auth'])->group(function () {
    // Route::prefix('admin')->name('admin.')->middleware(['role.kelurahan'])->group(function () {
    Route::prefix('admin')->name('admin.')->middleware(['role.kelurahan', 'setup.check'])->group(function () {
        // Route::get('/dashboard', function () {
        //     return view('admin.dashboard');
        // })->name('dashboard');
        Route::get('/beranda', [HomeController::class, 'edit'])->name('home.edit');
        Route::post('/beranda', [HomeController::class, 'update'])->name('home.update');
        
        Route::get('/tentang-kami/{about}/edit', [AboutController::class, 'edit'])
            ->name('about.edit');
        Route::patch('/tentang-kami/{about}', [AboutController::class, 'update'])
            ->name('about.update');
        Route::get('/sejarah', [HistoryController::class, 'edit'])->name('history.edit');
        Route::post('/sejarah', [HistoryController::class, 'update'])->name('history.update');
        Route::get('/organisasi/{organization}/edit', [OrganizationController::class, 'edit'])
            ->name('organization.edit');
        Route::patch('/organisasi/{organization}', [OrganizationController::class, 'update'])
            ->name('organization.update');
        Route::get('/galeri', [GalleryController::class, 'index'])
            ->name('gallery.index');
        Route::get('/galeri/create', [GalleryController::class, 'create'])
            ->name('gallery.create');
        Route::post('/galeri', [GalleryController::class, 'store'])
            ->name('gallery.store');
        Route::get('/galeri/{gallery}', [GalleryController::class, 'show'])
            ->name('gallery.show');
        Route::get('/galeri/{gallery}/edit', [GalleryController::class, 'edit'])
            ->name('gallery.edit');
        Route::patch('/galeri/{gallery}', [GalleryController::class, 'update'])
            ->name('gallery.update');
        Route::delete('/galeri/foto/{photo}', [GalleryController::class, 'destroyPhoto'])
            ->name('gallery.photo.destroy');
        Route::delete('/galeri/{gallery}', [GalleryController::class, 'destroy'])
            ->name('gallery.destroy');

        Route::resource('publikasi', PublicationController::class)
            ->names('publication')
            ->parameters(['publikasi' => 'publication'])
            ->except(['show']);
        Route::resource('infografis', InfographicController::class)
            ->names('infographic')
            ->parameters(['infografis' => 'infographic'])
            ->except(['show']);

        // // 1. custom route (pratinjau)
        // Route::post('/statistik/pratinjau', [StatisticalTableController::class, 'preview'])
        //     ->name('statistical-table.preview');
        // // 2. Resource tabel statistik utama
        // Route::resource('statistik', StatisticalTableController::class)
        //     ->names('statistical-table')
        //     ->parameters(['statistik' => 'statistical_table'])
        //     ->except(['show']);
        // 3. Nested resource untuk grafik (Disesuaikan agar seragam dengan parent-nya)
        // Route::resource('statistik.charts', StatisticChartController::class)
        //     ->names('statistic-chart') // Nanti kita bisa panggil route('statistic-chart.create')
        //     ->parameters([
        //         'statistik' => 'statistical_table', // Mengambil ID dari tabel statistik
        //         'charts' => 'statistic_chart'
        //     ])
        //     ->except(['index', 'show']);
        // Alur wajib "Pilih Template" sebelum bisa membuat tabel baru
        Route::get('/statistik/pilih-template', [StatisticTableEntryController::class, 'selectTemplate'])
            ->name('statistic-table-entries.select-template');
        Route::get('/statistik/buat/{statistic_template}', [StatisticTableEntryController::class, 'create'])
            ->name('statistic-table-entries.create');
        Route::post('/statistik/buat/{statistic_template}', [StatisticTableEntryController::class, 'store'])
            ->name('statistic-table-entries.store');
        Route::resource('statistik', StatisticTableEntryController::class)
            ->names('statistic-table-entries')
            ->parameters(['statistik' => 'statistic_table_entry'])
            ->except(['show', 'create', 'store']); // create/store custom di atas (butuh parameter template)
        Route::resource('statistik.charts', StatisticChartController::class)
            ->names('statistic-chart')
            ->parameters([
                'statistik' => 'statistic_table_entry',
                'charts' => 'statistic_chart',
            ])
            ->except(['index', 'show']);

        Route::get('/pengaturan', [SettingController::class, 'edit'])->name('setting.edit');
        Route::patch('/pengaturan', [SettingController::class, 'update'])->name('setting.update');
    });

    // Route::prefix('admin/setup')->name('admin.setup.')->middleware(['role.kelurahan'])->group(function () {
    Route::prefix('admin/setup')->name('admin.setup.')->middleware(['role.kelurahan', 'setup.check'])->group(function () {
        Route::get('/', [SetupController::class, 'index'])->name('index');
        
        // Step 1: Kelurahan (Setting)
        Route::get('/setting', [SetupController::class, 'setting'])->name('setting');
        Route::post('/setting', [SetupController::class, 'storeSetting'])->name('storeSetting');
        
        // Step 2: Organisasi
        Route::get('/organization', [SetupController::class, 'organization'])->name('organization');
        Route::post('/organization', [SetupController::class, 'storeOrganization'])->name('storeOrganization');

        // Step 3: Tentang Kami
        Route::get('/about', [SetupController::class, 'about'])->name('about');
        Route::post('/about', [SetupController::class, 'storeAbout'])->name('storeAbout');
    });
});
});