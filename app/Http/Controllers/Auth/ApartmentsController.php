<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Service;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ApartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Recupera tutti gli appartamenti collegati all'utente loggato
        $apartments = Apartment::where('user_id', auth()->id())->get();

        // Passa gli appartamenti
        return view('auth.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Form creazione
        $services = Service::all();
        return view('auth.apartments.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validazione dei dati
            $validated = $request->validate([
                'title' => 'required|max:250',
                'img' => 'nullable|image',
                'address' => 'required|max:100',
                'house_number' => 'required|integer',
                'postal_code' => 'required|max:20',
                'country' => 'required|size:2',
                'rooms' => 'required|integer|min:1',
                'beds' => 'required|integer|min:1',
                'bathrooms' => 'required|integer|min:1',
                'mq' => 'required|integer|min:1',
                'is_available' => 'required|boolean',
                'services' => 'nullable|array|exists:services,id',
            ]);

            // Chiamata API TomTom Structured Geocode
            $client = new Client(['verify' => false]);
            $apiKey = config('services.tomtom.key');

            // Parametri per la geocodifica strutturata
            $queryParams = [
                'countryCode' => urlencode($validated['country']),
                'limit' => 1,
                'streetNumber' => $validated['house_number'],
                'streetName' => urlencode($validated['address']),
                'postalCode' => urlencode($validated['postal_code']),
                'view' => 'Unified',
                'key' => $apiKey,
            ];

            // Costruzione dell'URL per la chiamata API TomTom Structured Geocode
            $url = "https://api.tomtom.com/search/2/structuredGeocode.json?" . http_build_query($queryParams);

            // Log dell'URL per debug
            Log::info('TomTom API URL: ' . $url);

            // Esecuzione della richiesta HTTP
            $response = $client->get($url);
            $data = json_decode($response->getBody(), true);

            // Controllo della risposta
            if (isset($data['results'][0]['position'])) {
                $latitude = $data['results'][0]['position']['lat'];
                $longitude = $data['results'][0]['position']['lon'];
            } else {
                // Errore se l'indirizzo non è stato trovato
                return redirect()->back()->withErrors('Indirizzo non trovato');
            }

            // Creazione del nuovo appartamento
            $apartment = new Apartment();
            $apartment->user_id = auth()->id();
            $apartment->title = $validated['title'];
            $apartment->img = $request->file('img') ? $request->file('img')->store('images', 'public') : null;
            $apartment->address = $validated['address'];
            $apartment->house_number = $validated['house_number'];
            $apartment->postal_code = $validated['postal_code'];
            $apartment->country = $validated['country'];
            $apartment->latitude = $latitude;
            $apartment->longitude = $longitude;
            $apartment->rooms = $validated['rooms'];
            $apartment->beds = $validated['beds'];
            $apartment->bathrooms = $validated['bathrooms'];
            $apartment->mq = $validated['mq'];
            $apartment->is_available = (bool) $validated['is_available'];

            // Salvataggio dell'appartamento
            $apartment->save();

            // Associa i servizi selezionati
            if ($request->has('services')) {
                $apartment->services()->sync($request->input('services'));
            }

            return redirect()->route('apartments.index')->with('success', 'Appartamento creato con successo');
        } catch (\Exception $e) {
            // Log dell'errore
            Log::error('Error creating apartment: ' . $e->getMessage());
            return redirect()->back()->withErrors('Si è verificato un errore durante la creazione dell\'appartamento.');
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // Recupera l'appartamento dal db utilizzando l'id
        $apartment = Apartment::findOrFail($id);

        // Passiamo i dati allo show
        return view('auth.apartments.show', compact('apartment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        // Recupera l'appartamento dal database utilizzando l'ID
        $apartment = Apartment::findOrFail($id);
        $services = Service::all();

        $apartmentServices = $apartment->services->pluck('id')->toArray();
        // Passa i dati dell'appartamento alla vista 'edit'
        return view('auth.apartments.edit', compact('apartment', 'services', 'apartmentServices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        // Validazione dei dati
        $validated = $request->validate([
            'title' => 'required|max:250',
            'img' => 'nullable|image',
            'address' => 'required|max:100',
            'house_number' => 'required|integer',
            'postal_code' => 'required|max:20',
            'country' => 'required|size:2',
            'rooms' => 'required|integer|min:1',
            'beds' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'mq' => 'required|integer|min:1',
            'is_available' => 'required|boolean',
            'services' => 'nullable|array|exists:services,id',
        ]);

        // Trova l'appartamento da aggiornare
        $apartment = Apartment::findOrFail($id);

        // Check if address-related fields have changed
        $addressChanged = $apartment->address !== $validated['address']
            || $apartment->house_number !== $validated['house_number']
            || $apartment->postal_code !== $validated['postal_code']
            || $apartment->country !== $validated['country'];

        // Aggiorna i dati dell'appartamento
        $apartment->title = $validated['title'];
        $apartment->address = $validated['address'];
        $apartment->house_number = $validated['house_number'];
        $apartment->postal_code = $validated['postal_code'];
        $apartment->country = $validated['country'];
        $apartment->rooms = $validated['rooms'];
        $apartment->beds = $validated['beds'];
        $apartment->bathrooms = $validated['bathrooms'];
        $apartment->mq = $validated['mq'];
        $apartment->is_available = $validated['is_available'];

        // Gestione dell'immagine
        if ($request->hasFile('img')) {
            // Elimina l'immagine esistente se presente
            if ($apartment->img) {
                Storage::disk('public')->delete($apartment->img);
            }
            // Salva la nuova immagine
            $apartment->img = $request->file('img')->store('images', 'public');
        }

        // Se i dettagli dell'indirizzo sono cambiati, aggiorna la latitudine e longitudine
        if ($addressChanged) {
            try {
                // Chiamata API TomTom Structured Geocode
                $client = new Client(['verify' => false]);
                $apiKey = config('services.tomtom.key');

                // Parametri per la geocodifica strutturata
                $queryParams = [
                    'countryCode' => urlencode($validated['country']),
                    'limit' => 1,
                    'streetNumber' => $validated['house_number'],
                    'streetName' => urlencode($validated['address']),
                    'postalCode' => urlencode($validated['postal_code']),
                    'view' => 'Unified',
                    'key' => $apiKey,
                ];

                // Costruzione dell'URL per la chiamata API TomTom Structured Geocode
                $url = "https://api.tomtom.com/search/2/structuredGeocode.json?" . http_build_query($queryParams);

                // Log dell'URL per debug
                Log::info('TomTom API URL: ' . $url);

                // Esecuzione della richiesta HTTP
                $response = $client->get($url);
                $data = json_decode($response->getBody(), true);

                // Controllo della risposta
                if (isset($data['results'][0]['position'])) {
                    $latitude = $data['results'][0]['position']['lat'];
                    $longitude = $data['results'][0]['position']['lon'];

                    // Aggiorna latitudine e longitudine
                    $apartment->latitude = $latitude;
                    $apartment->longitude = $longitude;
                } else {
                    // Errore se l'indirizzo non è stato trovato
                    return redirect()->back()->withErrors('Indirizzo non trovato');
                }
            } catch (\Exception $e) {
                // Log dell'errore
                Log::error('Error updating latitude and longitude: ' . $e->getMessage());
                return redirect()->back()->withErrors('Si è verificato un errore durante l\'aggiornamento della latitudine e longitudine.');
            }
        }

        // Salva le modifiche
        $apartment->save();

        // Associa i servizi selezionati
        if ($request->has('services')) {
            $apartment->services()->sync($request->input('services'));
        }

        return redirect()->route('apartments.index')->with('success', 'Appartamento aggiornato con successo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Trova l'appartamento da eliminare
        $apartment = Apartment::findOrFail($id);

        // Elimina l'immagine associata se presente
        if ($apartment->img) {
            Storage::disk('public')->delete($apartment->img);
        }

        // Elimina l'appartamento dal database
        $apartment->delete();

        return redirect()->route('apartments.index')->with('success', 'Appartamento eliminato con successo');
    }
}
