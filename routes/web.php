<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApartmentsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Rotta per la dashboard che richiede autenticazione e verifica dell'email
Route::get('/', function () {
    return view('auth.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rotte per la gestione del profilo dell'utente
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotte CRUD per la gestione degli appartamenti, accessibili solo agli utenti autenticati
Route::middleware('auth')->group(function () {
    Route::resource('apartments', ApartmentsController::class);
});

// Includi le rotte per l'autenticazione (login, registrazione, ecc.)
require __DIR__ . '/auth.php';
