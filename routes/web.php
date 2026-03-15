<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\AdminExamController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\AdminQuestionController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CandidateCourseController;
use App\Http\Controllers\CandidatePaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'marketing.massar')->name('home');
Route::view('/massar', 'marketing.massar')->name('marketing.massar');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [CandidateController::class, 'dashboard'])->name('dashboard');
    Route::get('/quiz', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/courses', [CandidateCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CandidateCourseController::class, 'show'])
        ->name('courses.show')
        ->missing(function () {
            return redirect()
                ->route('courses.index')
                ->with('error', 'Cours introuvable. Veuillez sélectionner un cours depuis la liste.');
        });
    Route::get('/courses/{course}/pdf', [CandidateCourseController::class, 'pdf'])->name('courses.pdf');
    Route::get('/payments', [CandidatePaymentController::class, 'index'])->name('payments.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/candidates', [AdminController::class, 'candidates'])->name('candidates.index');
        Route::resource('/courses', AdminCourseController::class)->except('show');
        Route::resource('/payments', AdminPaymentController::class)->except('show');
        Route::resource('/exams', AdminExamController::class)->except('show');
        Route::resource('/questions', AdminQuestionController::class)->except('show');
    });

require __DIR__.'/auth.php';
