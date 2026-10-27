<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\BiodataController;
use Illuminate\Support\Facades\Route;


Route::get('/', [BiodataController::class, 'resume'])->name('resume');
Route::get('/cv/{biodata}', [BiodataController::class, 'showResume'])->name('biodata.resume');

Route::get('/home',[HomeController::class, 'index']);


Route::post('question/store', [QuestionController::class, 'store'])
		->name('question.store');

Route::get('question/respon', [QuestionController::class, 'respon'])
		->name('question.respon');


Route::get('/dashboard', [BiodataController::class, 'index'])->name('dashboard');
Route::resource('biodata', BiodataController::class)
    ->except(['index', 'show'])
    ->parameters(['biodata' => 'biodata']);
