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
                return response()->json(['error' => 'L\'email ou le mot de passe est incorrect.'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // Retourner le token et les informations utilisateur
        return response()->json([
            'user' => JWTAuth::user(),
            'token' => $token
        ]);
    }

    // Déconnexion
    public function logout()
    {
        $token = JWTAuth::getToken();
        if ($token) {
            JWTAuth::invalidate($token);
            return response()->json(['message' => 'Déconnecté avec succès']);
        }

        return response()->json(['error' => 'Aucun token trouvé'], 400);
    }

    // Informations utilisateur connecté
    public function me()
    {
        return response()->json([
            'id' => auth()->user()->id,
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]);
    }
}
