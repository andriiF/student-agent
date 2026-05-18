<?php

use App\Http\Controllers\Api\FrontendAuthController;
use App\Http\Middleware\AuthenticateFrontendJwt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\QuestionController;

Route::middleware(['api', AuthenticateFrontendJwt::class])->group(function (): void {
    Route::resource('topic', TopicController::class);
    Route::resource('quiz', QuizController::class);
    Route::resource('question', QuestionController::class);
});

Route::prefix('auth')->middleware('api')->group(function (): void {
    Route::post('register', [FrontendAuthController::class, 'register']);
    Route::post('login', [FrontendAuthController::class, 'login']);

    Route::middleware(AuthenticateFrontendJwt::class)->get('me', [FrontendAuthController::class, 'me']);
});
