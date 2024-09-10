<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentsController extends Controller
{
    /**
     * Display a listing of the apartments.
     */
    public function index()
    {
        $apartments = Apartment::with('user')->get(); // Usa with per includere la relazione

        $apartments = $apartments->map(function ($apartment) {
            return [
                'id' => $apartment->id,
                'title' => $apartment->title,
                'address' => $apartment->address,
                'img' => $apartment->img,
                'latitude' => $apartment->latitude,
                'longitude' => $apartment->longitude,
                'rooms' => $apartment->rooms,
                'beds' => $apartment->beds,
                'bathrooms' => $apartment->bathrooms,
                'mq' => $apartment->mq,
                'is_avaible' => $apartment->is_avaible,
                'user' => [
                    'name' => $apartment->user->name,
                    'surname' => $apartment->user->surname,
                ],
            ];
        });

        return response()->json($apartments);
    }

    /**
     * Display the specified apartment.
     */
    public function show($id)
    {
        $apartment = Apartment::with('user')->findOrFail($id); // Trova l'appartamento per ID

        return response()->json([
            'id' => $apartment->id,
            'title' => $apartment->title,
            'address' => $apartment->address,
            'img' => $apartment->img,
            'latitude' => $apartment->latitude,
            'longitude' => $apartment->longitude,
            'rooms' => $apartment->rooms,
            'beds' => $apartment->beds,
            'bathrooms' => $apartment->bathrooms,
            'mq' => $apartment->mq,
            'is_avaible' => $apartment->is_avaible,
            'user' => [
                'name' => $apartment->user->name,
                'surname' => $apartment->user->surname,
            ],
        ]);
    }
}
