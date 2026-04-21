<?php

use App\Http\Controllers\Api\FrontendAuthController;
use App\Http\Middleware\AuthenticateFrontendJwt;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('register', [FrontendAuthController::class, 'register']);
    Route::post('login', [FrontendAuthController::class, 'login']);

    Route::middleware(AuthenticateFrontendJwt::class)->get('me', [FrontendAuthController::class, 'me']);
});
