<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Service;

class SearchController extends Controller
{
    /**
     * 
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
            'radius' => 'nullable|numeric|min:1',
            'min_rooms' => 'nullable|integer|min:1',
            'services' => 'nullable|array',
        ]);

        $query = $request->input('query');
        $radius = $request->input('radius', 20);
        $minRooms = $request->input('min_rooms', 1);
        $services = $request->input('services', []);

        $firstApartment = Apartment::select('latitude', 'longitude')
            ->where('address', 'like', '%' . $query . '%')
            ->first();

        if (!$firstApartment) {
            return response()->json([]);
        }

        $firstLat = $firstApartment->latitude;
        $firstLng = $firstApartment->longitude;

        $apartments = Apartment::select('id', 'title', 'address', 'img', 'latitude', 'longitude', 'rooms', 'beds', 'bathrooms', 'mq', 'is_available', 'user_id')
            ->with('user')
            ->with('services')
            ->get();

        $filteredApartments = $apartments->filter(function ($apartment) use ($firstLat, $firstLng, $radius, $minRooms, $services) {
            $distance = $this->calculateDistance($firstLat, $firstLng, $apartment->latitude, $apartment->longitude);

            $isWithinRadius = $distance <= $radius;
            $hasEnoughRooms = $apartment->rooms >= $minRooms;

            $hasRequiredServices = true;
            if (!empty($services)) {
                $apartmentServices = $apartment->services->pluck('name')->toArray();
                $hasRequiredServices = count(array_intersect($services, $apartmentServices)) === count($services);
            }

            return $isWithinRadius && $hasEnoughRooms && $hasRequiredServices;
        })->values();

        $apartmentsArray = $filteredApartments->map(function ($apartment) {
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
                'user' => [
                    'name' => $apartment->user->name,
                    'surname' => $apartment->user->surname,
                ],
                'services' => $apartment->services->pluck('name')->toArray(),
            ];
        })->toArray();

        return response()->json($apartmentsArray);
    }

    /**
     * 
     *
     * @param float $lat1
     * @param float $lng1
     * @param float $lat2
     * @param float $lng2
     * @return float
     */
    private function calculateDistance($lat1, $lng1, $lat2, $lng2): float
    {
        $earthRadius = 6371;

        $latFrom = deg2rad($lat1);
        $lngFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lngTo = deg2rad($lng2);

        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)));

        return $earthRadius * $angle;
    }

    // ...

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableServices()
    {
        try {
            // Recupera tutti i servizi dalla tabella services
            $services = Service::select('id', 'name')->get();
            return response()->json($services);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Errore nel recupero dei servizi'], 500);
        }
    }
}
