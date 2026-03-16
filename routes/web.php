<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\AdminCandidateController;
use App\Http\Controllers\AdminExamController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\AdminQuestionController;
use App\Http\Controllers\AdminCourseResourceController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CandidateCourseController;
use App\Http\Controllers\CandidatePaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', function (Request $request, string $locale) {
    $supportedLocales = config('app.supported_locales', ['fr', 'ar']);
    $defaultLocale = config('app.locale', 'fr');

    abort_unless(in_array($locale, $supportedLocales, true), 404);

    $request->session()->put('locale', $locale);

    $redirectTo = url()->previous();
    $currentSwitchUrl = route('locale.switch', ['locale' => $locale], false);

    if (! $redirectTo || str_contains($redirectTo, $currentSwitchUrl)) {
        $redirectTo = route('home');
    }

    return redirect($redirectTo)->cookie('massar_locale', $locale, 60 * 24 * 365);
})->name('locale.switch');

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
    Route::get('/courses/{course}/media', [CandidateCourseController::class, 'media'])->name('courses.media');
    Route::get('/courses/{course}/pdf', [CandidateCourseController::class, 'pdf'])->name('courses.pdf');
    Route::get('/courses/{course}/resources/{resource}/file', [CandidateCourseController::class, 'resourceFile'])->name('courses.resources.file');
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
        Route::resource('/candidates', AdminCandidateController::class)->except(['create', 'store']);
        Route::resource('/courses', AdminCourseController::class)->except('show');
        Route::resource('/courses.resources', AdminCourseResourceController::class)->except('show');
        Route::resource('/payments', AdminPaymentController::class)->except('show');
        Route::resource('/exams', AdminExamController::class)->except('show');
        Route::resource('/questions', AdminQuestionController::class)->except('show');
    });

require __DIR__.'/auth.php';
