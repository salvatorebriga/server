<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApartmentsController;
use App\Http\Controllers\Api\AutocompleteController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\TomTomMapContreller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/apartments', [ApartmentsController::class, 'index'])->name('apartments.index');
Route::get('/apartments/{id}', [ApartmentsController::class, 'show'])->name('apartments.show');
// Rotta per la ricerca di appartamenti in base ai criteri come servizi, zona, ecc.
Route::get('/search', [SearchController::class, 'search']);
// fetch dei servizi
Route::get('/search/services', [SearchController::class, 'getAvailableServices']);
// Rotta per l'autocompletamento di TomTom
Route::get('/autocomplete', [AutocompleteController::class, 'autocomplete']);
// Rotta per l'inserimento dei messaggi
Route::post('/message', [MessageController::class, 'storeMessage']);

// Rotta per la mappa tomtom
Route::get('/map', [TomTomMapContreller::class, 'mapData']);
