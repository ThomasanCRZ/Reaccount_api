<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Inscription
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'firstname' => $request->firstname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Crée un token Sanctum pour le nouvel utilisateur
        $token = $user->createToken('reaccount-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // Connexion
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants fournis sont incorrects.'],
            ]);
        }

        // Supprime les anciens tokens pour éviter accumulation
        $user->tokens()->delete();

        // Crée un nouveau token
        $token = $user->createToken('reaccount-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Récupération de l'utilisateur connecté
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    // Déconnexion
    public function logout(Request $request)
    {
        // Supprime tous les tokens de l'utilisateur connecté
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }
}