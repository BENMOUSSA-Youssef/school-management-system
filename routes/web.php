<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ResultController;

// The first page people see
Route::get('/', [LoginController::class, 'showLoginForm']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [App\Http\Controllers\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\RegisterController::class, 'register']);

use App\Http\Controllers\StudentDashboardController;

// Student Dashboard (accessible to students only)
Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard')->middleware(['auth', 'role:student']);

// Using '/dashboard' for your index.blade.php (teachers only)
Route::get('/dashboard', function () {
    return view('index'); 
})->middleware(['auth', 'role:teacher']);

// Students routes (teachers only)
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Modules routes
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::post('/modules', [ModuleController::class, 'store'])->name('modules.store');
    Route::get('/modules/{module}/edit', [ModuleController::class, 'edit'])->name('modules.edit');
    Route::put('/modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');

    // Grades routes
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/{grade}/edit', [GradeController::class, 'edit'])->name('grades.edit');
    Route::put('/grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
    Route::delete('/grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');
    Route::post('/grades/bulk-update', [GradeController::class, 'bulkUpdate'])->name('grades.bulk-update');

    // Results routes
    Route::get('/results', [ResultController::class, 'index'])->name('results.index');
});

// Profile (accessible to all authenticated users)
Route::get('/profile', function () {
    return view('profile');
})->middleware('auth');