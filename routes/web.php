<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/admin/upload-quiz', [QuizController::class, 'uploadQuiz'])->name('upload-quiz');
    Route::get('/show-quiz-list', [QuizController::class, 'showQuizList'])->name('quiz-list');
    Route::get('/quiz-submission-info/{id}', [QuizController::class, 'quizSubmissionInfo'])->name('quiz-submission-info');
    Route::get('/quiz-info-to-answer/{id}', [QuizController::class, 'quizInfoToAnswer'])->name('quiz-info-to-answer');
    Route::post('/add-submission-to-quiz', [QuizController::class, 'addSubmission'])->name('add-submission');
});

require __DIR__.'/auth.php';
