<?php

use App\Http\Controllers\Api\ApartmentsController;
use Illuminate\Support\Facades\Route;

Route::get('/apartments', [ApartmentsController::class, 'index'])->name('apartments.index');
Route::get('/apartments/{id}', [ApartmentsController::class, 'show'])->name('apartments.show');
