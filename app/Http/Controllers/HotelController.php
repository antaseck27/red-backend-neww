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
            return [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'address' => $hotel->address,
                'price_per_night' => $hotel->price_per_night,
                'currency' => $hotel->currency,
                'image' => $hotel->image,
                'image_url' => $hotel->image
                    ? asset('storage/' . $hotel->image)
                    : null,
                'created_at' => $hotel->created_at,
                'updated_at' => $hotel->updated_at,
            ];
        });

        return response()->json($hotels);
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
            $validated['image'] = $request
                ->file('image')
                ->store('hotels', 'public');
        }

        $hotel = Hotel::create($validated);

        return response()->json([
            'message' => 'Hôtel créé avec succès',
            'hotel' => [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'address' => $hotel->address,
                'price_per_night' => $hotel->price_per_night,
                'currency' => $hotel->currency,
                'image' => $hotel->image,
                'image_url' => $hotel->image
                    ? asset('storage/' . $hotel->image)
                    : null,
            ],
        ], 201);
    }
}
