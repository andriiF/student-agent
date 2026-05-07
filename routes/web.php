<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\FrontendUsers\FrontendUserController;
use App\Http\Controllers\Admin\Quiz\AnswerController;
use App\Http\Controllers\Admin\Quiz\QuestionController;
use App\Http\Controllers\Admin\Quiz\QuizController;
use App\Http\Controllers\Admin\Quiz\TopicController;

Route::inertia('/', 'Home', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('frontendUsers', FrontendUserController::class)->except(['show']);
    Route::resource('quizzes', QuizController::class)->except(['show']);
    Route::resource('topics', TopicController::class)->except(['show']);
    Route::resource('questions', QuestionController::class)->except(['show']);
    Route::resource('answers', AnswerController::class)->except(['show']);
});

require __DIR__ . '/settings.php';
