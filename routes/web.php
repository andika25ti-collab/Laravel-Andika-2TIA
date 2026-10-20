<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelangganController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/home',[HomeController::class, 'index']);


Route::post('question/store', [QuestionController::class, 'store'])
		->name('question.store');

Route::get('question/respon', [QuestionController::class, 'respon'])
		->name('question.respon');


Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::resource('pelanggan', PelangganController::class);