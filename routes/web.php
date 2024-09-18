<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApartmentsController;
use App\Http\Controllers\AutocompleteController;
use Illuminate\Support\Facades\Auth;

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

// Rotta per la home che gestisce diversi casi per utenti autenticati e non
Route::get('/', function () {
    // Se l'utente è autenticato
    if (Auth::check()) {
        return redirect()->route('dashboard'); // Reindirizza alla dashboard se autenticato
    }

    // Se l'utente non è autenticato, reindirizzalo alla pagina di login
    return redirect()->route('login');
});

// Rotta per la dashboard che richiede solo autenticazione
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
// Rotte per la gestione del profilo dell'utente
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('apartments', ApartmentsController::class);
});

// Rotta per l'autocompletamento di TomTom
Route::get('/autocomplete', [AutocompleteController::class, 'autocomplete']);


// Includi le rotte per l'autenticazione (login, registrazione, ecc.)
require __DIR__ . '/auth.php';
