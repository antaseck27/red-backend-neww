<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Inscription
    public function register(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Générer un token JWT
        $token = JWTAuth::fromUser($user);

        // Retourner l'utilisateur et le token
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // Connexion
    public function login(Request $request)
    {
        // Validation des données
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Tentative d'authentification avec les identifiants
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Identifiants invalides'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // Si la connexion réussit, retourner le token et les informations utilisateur
        return response()->json([
            'user' => auth()->user(),
            'token' => $token
        ]);
    }

    // Déconnexion
    public function logout()
    {
        // Invalider le token JWT
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Déconnecté avec succès']);
    }

    // Informations utilisateur connecté
    public function me()
    {
        // Retourner les informations de l'utilisateur authentifié
        return response()->json(auth()->user());
    }
}
