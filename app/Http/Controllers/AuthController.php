<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Registrazione
    public function register(Request $request)
    {
        // validate register input
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',  // Aggiunta la validazione per il cognome
            'email' => 'required|string|email|max:255|unique:users',
            'date_of_birth' => 'required|date|before:today',  // Aggiunta la validazione per la data di nascita (deve essere una data passata)
            'password' => 'required|string|min:8|confirmed',
        ]);

        // create a new user with hashed password
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,  // Aggiunto il cognome
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,  // Aggiunta la data di nascita
            'password' => Hash::make($request->password),
        ]);

        // response
        return response()->json(['message' => 'User registered successfully!'], 201);
    }


    //Login
    public function login(Request $request)
    {
        // Validate email and password inputs
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt authentication using the provided credentials
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Return error response if authentication fails
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Create a new token for the authenticated user
        $token = $user->createToken('token-name')->plainTextToken;

        // Return success response with the token and user data
        return response()->json(['token' => $token, 'user' => $user], 200);
    }


    // Logout
    public function logout(Request $request)
    {
        // Delete the user's current access token
        $request->user()->currentAccessToken()->delete();

        // Return a success message indicating the user has logged out
        return response()->json(['message' => 'Logged out successfully!'], 200);
    }


    // Recupera i dettagli dell'utente autenticato
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
