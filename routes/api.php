<?php

use App\Http\Controllers\Api\ApartmentsController;
use App\Http\Controllers\Api\AutocompleteController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/apartments', [ApartmentsController::class, 'index'])->name('apartments.index');
Route::get('/apartments/{id}', [ApartmentsController::class, 'show'])->name('apartments.show');
// Rotta per la ricerca di appartamenti in base ai criteri come servizi, zona, ecc.
Route::get('/search', [SearchController::class, 'search']);
// fetch dei servizi
Route::get('/search/services', [SearchController::class, 'getAvailableServices']);


// Rotta per l'autocompletamento di TomTom
Route::get('/autocomplete', [AutocompleteController::class, 'autocomplete']);
