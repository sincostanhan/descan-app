<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tentang-kami', [AboutController::class, 'index'])
    ->name('about.index');
Route::get('/profil/sejarah', [HistoryController::class, 'index'])
    ->name('history.index');
Route::get('/organisasi', [OrganizationController::class, 'index'])
    ->name('organization.index');
Route::get('/galeri', [GalleryController::class, 'indexPublik'])
    ->name('gallery.index');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/tentang-kami/{about}/edit', [AboutController::class, 'edit'])
        ->name('about.edit');
    Route::patch('/tentang-kami/{about}', [AboutController::class, 'update'])
        ->name('about.update');
    Route::get('/sejarah/{history}/edit', [HistoryController::class, 'edit'])
        ->name('history.edit');
    Route::patch('/sejarah/{history}', [HistoryController::class, 'update'])
        ->name('history.update');
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
    Route::delete('/galeri/{gallery}', [GalleryController::class, 'destroy'])
        ->name('gallery.destroy');
});