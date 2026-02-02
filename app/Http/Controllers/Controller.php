<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class HotelController extends Controller
{
    /* ===========================
    |  LISTE DES HÔTELS
    |===========================*/
    public function index()
    {
        // Récupérer l'URL de l'API à partir des variables d'environnement
        $apiUrl = env('VITE_API_URL');

        // Appeler l'API externe pour récupérer la liste des hôtels
        $response = Http::get("{$apiUrl}/hotels");

        // Vérifier si la requête a réussi
        if ($response->successful()) {
            $hotels = $response->json();

            // Ajouter l'URL publique de l'image en utilisant 'storage'
            $hotels = array_map(function ($hotel) {
                if (isset($hotel['image'])) {
                    // Construire l'URL de l'image avec 'storage' (dossier public)
                    $hotel['image'] = asset('storage/' . $hotel['image']);
                }
                return $hotel;
            }, $hotels);

            return response()->json($hotels);
        }

        return response()->json(['error' => 'Erreur de récupération des hôtels'], 500);
    }

    /* ===========================
    |  CRÉER UN HÔTEL
    |===========================*/
    public function store(Request $request)
    {
        // Validation des données envoyées
        $validated = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'price_per_night' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'image' => 'nullable|image|max:2048',
        ]);

        // Upload image dans storage/public/hotels
        if ($request->hasFile('image')) {
            $validated['image'] = $request
                ->file('image')
                ->store('hotels', 'public'); // Stockage dans storage/app/public/hotels
        }

        // Créer un nouvel hôtel dans la base de données
        $hotel = Hotel::create($validated);

        return response()->json($hotel, 201);
    }
}
