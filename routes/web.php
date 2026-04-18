<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Student;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ─── Public ───────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ─── Auth ─────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Admin ────────────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Quizzes
        Route::resource('quizzes', Admin\QuizController::class);
        Route::post('quizzes/{quiz}/toggle-publish', [Admin\QuizController::class, 'togglePublish'])
            ->name('quizzes.toggle-publish');

        // Questions (nested under quizzes)
        Route::prefix('quizzes/{quiz}/questions')->name('quizzes.questions.')->group(function () {
            Route::get('/',             [Admin\QuestionController::class, 'index'])->name('index');
            Route::get('/create',       [Admin\QuestionController::class, 'create'])->name('create');
            Route::post('/',            [Admin\QuestionController::class, 'store'])->name('store');
            Route::get('/{question}/edit',   [Admin\QuestionController::class, 'edit'])->name('edit');
            Route::put('/{question}',        [Admin\QuestionController::class, 'update'])->name('update');
            Route::delete('/{question}',     [Admin\QuestionController::class, 'destroy'])->name('destroy');
            Route::post('/reorder',          [Admin\QuestionController::class, 'reorder'])->name('reorder');
        });

        // Categories
        Route::resource('categories', Admin\CategoryController::class);
    });

// ─── Student ──────────────────────────────────────────────────────
Route::prefix('student')
    ->name('student.')
    ->middleware(['auth', 'role:student'])
    ->group(function () {

        Route::get('/dashboard', [Student\DashboardController::class, 'index'])
            ->name('dashboard');

        // Quizzes
        Route::get('/quizzes',               [Student\QuizController::class, 'index'])->name('quizzes.index');
        Route::get('/quizzes/{quiz}',         [Student\QuizController::class, 'show'])->name('quizzes.show');
        Route::post('/quizzes/{quiz}/start',  [Student\QuizController::class, 'start'])->name('quizzes.start');

        Route::get('/quizzes/{quiz}/attempt/{attempt}',
            [Student\QuizController::class, 'take'])->name('quizzes.take');
        Route::post('/quizzes/{quiz}/attempt/{attempt}/save-answer',
            [Student\QuizController::class, 'saveAnswer'])->name('quizzes.save-answer');
        Route::post('/quizzes/{quiz}/attempt/{attempt}/submit',
            [Student\QuizController::class, 'submit'])->name('quizzes.submit');

        // Results
        Route::get('/results',           [Student\ResultController::class, 'index'])->name('results.index');
        Route::get('/results/{attempt}', [Student\ResultController::class, 'show'])->name('results.show');
    });
