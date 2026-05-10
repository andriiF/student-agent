<?php

use App\Http\Controllers\Api\FrontendAuthController;
use App\Http\Middleware\AuthenticateFrontendJwt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TopicController;

Route::middleware(['api', AuthenticateFrontendJwt::class])->group(function (): void {
    Route::get('loaddecs', [TopicController::class, 'index']);
});

Route::prefix('auth')->middleware('api')->group(function (): void {
    Route::post('register', [FrontendAuthController::class, 'register']);
    Route::post('login', [FrontendAuthController::class, 'login']);

    Route::middleware(AuthenticateFrontendJwt::class)->get('me', [FrontendAuthController::class, 'me']);
});
