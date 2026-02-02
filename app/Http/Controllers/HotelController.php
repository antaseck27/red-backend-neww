<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    /* ===========================
    |  LISTE DES HÔTELS
    |===========================*/
    public function index()
    {
        $hotels = Hotel::all()->map(function ($hotel) {
            if ($hotel->image) {
                $hotel->image = asset('storage/' . $hotel->image);
            }
            return $hotel;
        });

        return response()->json($hotels);
    }

    /* ===========================
    |  AFFICHER UN HÔTEL
    |===========================*/
    public function show(Hotel $hotel)
    {
        if ($hotel->image) {
            $hotel->image = asset('storage/' . $hotel->image);
        }

        return response()->json($hotel);
    }

    /* ===========================
    |  CRÉER UN HÔTEL
    |===========================*/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'price_per_night' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hotels', 'public');
        }

        $hotel = Hotel::create($validated);

        return response()->json($hotel, 201);
    }

    /* ===========================
    |  MODIFIER UN HÔTEL
    |===========================*/
    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'address' => 'sometimes|string',
            'price_per_night' => 'sometimes|numeric',
            'currency' => 'sometimes|string|max:3',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($hotel->image) {
                Storage::disk('public')->delete($hotel->image);
            }

            $validated['image'] = $request->file('image')->store('hotels', 'public');
        }

        $hotel->update($validated);

        return response()->json($hotel);
    }

    /* ===========================
    |  SUPPRIMER UN HÔTEL
    |===========================*/
    public function destroy(Hotel $hotel)
    {
        if ($hotel->image) {
            Storage::disk('public')->delete($hotel->image);
        }

        $hotel->delete();

        return response()->json(['message' => 'Hôtel supprimé']);
    }
}
