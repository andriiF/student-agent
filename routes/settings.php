<?php

use App\Http\Controllers\Admin\Settings\ProfileController;
use App\Http\Controllers\Admin\Settings\FrontendUserController;
use App\Http\Controllers\Admin\Settings\SecurityController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/security', [SecurityController::class, 'edit'])->name('security.edit');

    Route::put('settings/password', [SecurityController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('user-password.update');

    Route::get('settings/frontend-users', [FrontendUserController::class, 'index'])->name('frontend-users.index');
    Route::post('settings/frontend-users', [FrontendUserController::class, 'store'])->name('frontend-users.store');
    Route::patch('settings/frontend-users/{frontendUser}', [FrontendUserController::class, 'update'])->name('frontend-users.update');
    Route::delete('settings/frontend-users/{frontendUser}', [FrontendUserController::class, 'destroy'])->name('frontend-users.destroy');

    Route::inertia('settings/appearance', 'settings/Appearance')->name('appearance.edit');
});
