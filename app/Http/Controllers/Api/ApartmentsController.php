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
        // Usa with per includere la relazione con user e sponsors
        $apartments = Apartment::with(['user', 'sponsors'])->get();

        // Mappa gli appartamenti per strutturare il risultato API
        $apartments = $apartments->map(function ($apartment) {
            $isSponsored = $apartment->sponsors->isNotEmpty(); // Verifica se ci sono sponsorizzazioni

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
                'is_available' => $apartment->is_available,
                'is_sponsored' => $isSponsored, // Aggiungi questo campo
                'user' => [
                    'name' => $apartment->user->name,
                    'surname' => $apartment->user->surname,
                ],
                // Aggiungi i dati della sponsorizzazione se esistenti
                'sponsor' => $isSponsored ? [
                    'type' => $apartment->sponsors->first()->pivot->type,
                    'time' => $apartment->sponsors->first()->pivot->time,
                    'start_date' => $apartment->sponsors->first()->pivot->start_date,
                    'end_date' => $apartment->sponsors->first()->pivot->end_date,
                ] : null,
            ];
        });

        return response()->json($apartments);
    }

    public function storeViewStat(Request $request, $apartmentId)
    {
        $ipAddress = $request->ip();

        // Controlla se l'IP ha già visualizzato questo appartamento nelle ultime 24 ore
        $existingStat = \App\Models\Statistic::where('apartment_id', $apartmentId)
            ->where('ip_address', $ipAddress)
            ->where('created_at', '>=', now()->subDay())
            ->first();

        if (!$existingStat) {
            \App\Models\Statistic::create([
                'apartment_id' => $apartmentId,
                'ip_address' => $ipAddress,
            ]);
        }
    }


    /**
     * Display the specified apartment.
     */
    public function show($id, Request $request)
    {
        $apartment = Apartment::with(['user', 'sponsors', 'services'])->findOrFail($id);

        $this->storeViewStat($request, $id);

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
            'is_available' => $apartment->is_available,
            'user' => [
                'name' => $apartment->user->name,
                'surname' => $apartment->user->surname,
            ],
            'sponsor' => $apartment->sponsors->isNotEmpty() ? [
                'type' => $apartment->sponsors->first()->pivot->type,
                'time' => $apartment->sponsors->first()->pivot->time,
                'start_date' => $apartment->sponsors->first()->pivot->start_date,
                'end_date' => $apartment->sponsors->first()->pivot->end_date,
            ] : null,
            'services' => $apartment->services->map(function ($service) {
                return [
                    'name' => $service->name,
                ];
            }),
        ]);
    }
}
