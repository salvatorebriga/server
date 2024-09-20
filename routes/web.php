<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApartmentsController;
use App\Http\Controllers\AutocompleteController;
use App\Http\Controllers\Auth\MessageController;
use App\Http\Controllers\Auth\SponsorshipController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('apartments', ApartmentsController::class);
    Route::delete('/messages/multiple', [MessageController::class, 'destroyMultiple'])->name('messages.destroyMultiple');
    Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/sponsorship/store', [SponsorshipController::class, 'store'])->name('sponsorship.store');
});

Route::get('/autocomplete', [AutocompleteController::class, 'autocomplete']);



require __DIR__ . '/auth.php';
