<?php

use App\Http\Controllers\Vendor\VendorController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::prefix('settings')->name('settings.')->middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        return view('livewire.settings.profile');
    })->name('profile');

    Route::get('/password', function () {
        return view('livewire.settings.password');
    })->name('password');

    Route::get('/appearance', function () {
        return view('livewire.settings.appearance');
    })->name('appearance');
});


Route::get('vendor/register', [VendorController::class, 'showForm'])->name('vendor.form');
Route::post('vendor/register', [VendorController::class, 'submitForm'])->name('vendor.register');

require __DIR__.'/auth.php';
