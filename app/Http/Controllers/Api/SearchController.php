<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    // Chiave API per TomTom, recuperata dalle variabili di ambiente
    protected $tomTomApiKey;

    public function __construct()
    {
        $this->tomTomApiKey = env('TOMTOM_API_KEY'); // Recupera la chiave API dalle variabili di ambiente
    }

    /**
     * Ricerca gli appartamenti in base ai criteri forniti.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Recupera i criteri di ricerca dalla richiesta
        $query = $request->input('query');
        $services = $request->input('services'); // Filtri per servizi
        $zone = $request->input('zone'); // Filtri per zona (non utilizzato nel codice, ma potrebbe essere implementato)
        $minRooms = $request->input('minRooms'); // Numero minimo di stanze
        $minBeds = $request->input('minBeds'); // Numero minimo di posti letto
        $radius = $request->input('radius', 20); // Raggio di ricerca, valore di default 20 km
        $address = $request->input('address'); // Indirizzo fornito dall'utente

        // Recupera la latitudine e longitudine utilizzando l'API di TomTom
        $geoData = $this->getCoordinatesFromAddress($address);

        // Se l'indirizzo non è valido, ritorna un errore
        if (!$geoData) {
            return response()->json(['error' => 'Invalid address'], 400);
        }

        $lat = $geoData['lat']; // Latitudine
        $lon = $geoData['lon']; // Longitudine

        // Calcola i confini della ricerca basati sul raggio
        $earthRadius = 6371; // Raggio della Terra in km
        $maxLat = $lat + rad2deg($radius / $earthRadius); // Latitudine massima
        $minLat = $lat - rad2deg($radius / $earthRadius); // Latitudine minima
        $maxLon = $lon + rad2deg($radius / $earthRadius / cos(deg2rad($lat))); // Longitudine massima
        $minLon = $lon - rad2deg($radius / $earthRadius / cos(deg2rad($lat))); // Longitudine minima

        // Esegui la query per trovare gli appartamenti
        $apartments = Apartment::where('title', 'like', "%$query%")
            ->whereBetween('latitude', [$minLat, $maxLat]) // Filtra per latitudine
            ->whereBetween('longitude', [$minLon, $maxLon]) // Filtra per longitudine
            ->when($services, function ($q) use ($services) {
                return $q->whereHas('services', function ($q2) use ($services) {
                    $q2->whereIn('id', $services); // Filtra per servizi
                });
            })
            ->when($minRooms, function ($q) use ($minRooms) {
                return $q->where('rooms', '>=', $minRooms); // Filtra per numero minimo di stanze
            })
            ->when($minBeds, function ($q) use ($minBeds) {
                return $q->where('beds', '>=', $minBeds); // Filtra per numero minimo di posti letto
            })
            ->orderBy(DB::raw("ST_Distance_Sphere(point(longitude, latitude), point($lon, $lat))")) // Ordina per distanza
            ->get();

        // Ritorna i risultati della ricerca come JSON
        return response()->json($apartments);
    }

    /**
     * Ottiene latitudine e longitudine dall'indirizzo usando l'API di TomTom.
     *
     * @param string $address
     * @return array|null
     */
    protected function getCoordinatesFromAddress($address)
    {
        $response = Http::get('https://api.tomtom.com/search/2/geocode/' . urlencode($address) . '.json', [
            'key' => $this->tomTomApiKey // Chiave API di TomTom
        ]);

        // Verifica se la risposta è valida e contiene risultati
        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data['results'])) {
                $location = $data['results'][0]['position']; // Recupera posizione
                return [
                    'lat' => $location['lat'], // Latitudine
                    'lon' => $location['lon']  // Longitudine
                ];
            }
        }

        // Ritorna null se l'indirizzo non è valido o non ci sono risultati
        return null;
    }
}
