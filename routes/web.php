<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\FrontendUsers\FrontendUserController;

Route::inertia('/', 'Home', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('frontendUsers', FrontendUserController::class)->except(['show']);
});

require __DIR__ . '/settings.php';
