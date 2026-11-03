<?php

use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BiodataController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BiodataController::class, 'resume'])->name('resume');
Route::get('/cv/{biodata}', [BiodataController::class, 'showResume'])->name('biodata.resume');
Route::get('/home', [HomeController::class, 'index']);

Route::post('question/store', [QuestionController::class, 'store'])
    ->name('question.store');
Route::get('question/respon', [QuestionController::class, 'respon'])
    ->name('question.respon');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');
});

Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'can:access-admin'])->group(function () {
    Route::get('/dashboard', [BiodataController::class, 'index'])->name('dashboard');
    Route::resource('biodata', BiodataController::class)
        ->except(['index', 'show'])
        ->parameters(['biodata' => 'biodata']);

    Route::resource('pelanggan', PelangganController::class);
    Route::resource('admin/users', UserController::class)
        ->except('show')
        ->names('admin.users');
});
