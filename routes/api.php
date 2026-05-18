<?php

use App\Http\Controllers\Api\FrontendAuthController;
use App\Http\Middleware\AuthenticateFrontendJwt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuizPlayController;

Route::middleware(['api', AuthenticateFrontendJwt::class])->group(function (): void {
    Route::resource('topic', TopicController::class);
    Route::resource('quiz', QuizController::class);
    Route::resource('question', QuestionController::class);
    Route::get('quizplay/{quiz}', [QuizPlayController::class, 'show']);
    Route::post('quizplay/{quiz}', [QuizPlayController::class, 'saveAnswer']);
    Route::delete('quizplay/{quiz}', [QuizPlayController::class, 'destroy']);
});

Route::prefix('auth')->middleware('api')->group(function (): void {
    Route::post('register', [FrontendAuthController::class, 'register']);
    Route::post('login', [FrontendAuthController::class, 'login']);

    Route::middleware(AuthenticateFrontendJwt::class)->get('me', [FrontendAuthController::class, 'me']);
    Route::middleware(AuthenticateFrontendJwt::class)->get('getProgress', [FrontendAuthController::class, 'getProgress']);
});
