<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Aggiunta della validazione per surname e date_of_birth
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'], // Validazione per il cognome
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'date_of_birth' => ['required', 'date', 'before:today'], // Validazione per la data di nascita
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Creazione del nuovo utente con surname e date_of_birth
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname, // Aggiungi il cognome
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth, // Aggiungi la data di nascita
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
