<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabController;

Route::redirect('/', '/jobs');
Route::view('/jobs', 'jobs')->name('jobs');
Route::view('/hiring/confirmation', 'hiring-confirmation')->name('hiring.confirmation');
Route::get('/workspace', [HomeController::class, 'index'])->name('home');
Route::get('/labs/{slug}', [LabController::class, 'show'])->name('lab.show');
