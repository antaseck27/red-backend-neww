<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    // Liste tous les hôtels
    public function index()
    {
        $hotels = Hotel::all();
        return response()->json($hotels);
    }

    // Crée un nouvel hôtel (JWT requis)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'price_per_night' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'address', 'price_per_night', 'currency']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('hotels', 'public');
            $data['image'] = $path;
        }

        $hotel = Hotel::create($data);

        return response()->json($hotel, 201);
    }
}
