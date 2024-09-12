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

        // Passa gli appartamenti alla vista
        return view('auth.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Recupera tutti i servizi da mostrare nel form di creazione
        $services = Service::all();
        return view('auth.apartments.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validazione dei dati in ingresso
            $validated = $request->validate([
                'title' => 'required|max:250',
                'img' => 'nullable|image', // L'immagine è opzionale ma deve essere un file di tipo immagine
                'address' => 'required|max:100',
                'rooms' => 'required|integer|min:1',
                'beds' => 'required|integer|min:1',
                'bathrooms' => 'required|integer|min:1',
                'mq' => 'required|integer|min:1',
                'is_available' => 'required|boolean',
                'services' => 'required|min:1|array|exists:services,id', // Verifica che ci sia almeno un servizio selezionato
            ]);

            // Chiamata all'API TomTom per la geocodifica dell'indirizzo
            $client = new Client(['verify' => false]);
            $apiKey = config('services.tomtom.key');
            $queryParams = [
                'query' => urlencode($validated['address']), // Usa l'indirizzo per la geocodifica
                'key' => $apiKey,
            ];
            $url = 'https://api.tomtom.com/search/2/geocode/' . $queryParams['query'] . '.json?view=Unified&key=' . $queryParams['key'];

            // Log dell'URL per debug
            Log::info('TomTom API URL: ' . $url);

            // Esecuzione della richiesta HTTP e decodifica del JSON di risposta
            $response = $client->get($url);
            $data = json_decode($response->getBody(), true);

            // Se la posizione è stata trovata, ottieni latitudine e longitudine
            if (isset($data['results'][0]['position'])) {
                $latitude = $data['results'][0]['position']['lat'];
                $longitude = $data['results'][0]['position']['lon'];
            } else {
                // Se l'indirizzo non è stato trovato, mostra un messaggio di errore
                return redirect()->back()->withErrors('Indirizzo non trovato');
            }

            // Creazione di un nuovo oggetto Apartment e salvataggio dei dati
            $apartment = new Apartment();
            $apartment->user_id = auth()->id();
            $apartment->title = $validated['title'];
            // Salvataggio dell'immagine caricata o impostazione a null se non esiste
            $apartment->img = $request->file('img') ? $request->file('img')->store('images', 'public') : null;
            $apartment->address = $validated['address'];
            $apartment->latitude = $latitude;
            $apartment->longitude = $longitude;
            $apartment->rooms = $validated['rooms'];
            $apartment->beds = $validated['beds'];
            $apartment->bathrooms = $validated['bathrooms'];
            $apartment->mq = $validated['mq'];
            $apartment->is_available = (bool) $validated['is_available'];

            // Salva il nuovo appartamento
            $apartment->save();

            // Associa i servizi selezionati all'appartamento
            if ($request->has('services')) {
                $apartment->services()->sync($request->input('services'));
            }

            // Reindirizza l'utente alla lista degli appartamenti con un messaggio di successo
            return redirect()->route('apartments.index')->with('success', 'Appartamento creato con successo');
        } catch (\Exception $e) {
            // Log dell'errore e ritorno alla pagina precedente con un messaggio di errore
            Log::error('Error creating apartment: ' . $e->getMessage());
            return redirect()->back()->withErrors('Si è verificato un errore durante la creazione dell\'appartamento.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // Recupera l'appartamento dal database tramite l'ID
        $apartment = Apartment::findOrFail($id);

        // Passa i dati dell'appartamento alla vista "show"
        return view('auth.apartments.show', compact('apartment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        // Recupera l'appartamento e i servizi associati per la modifica
        $apartment = Apartment::findOrFail($id);
        $services = Service::all();
        $apartmentServices = $apartment->services->pluck('id')->toArray();

        // Passa i dati alla vista "edit"
        return view('auth.apartments.edit', compact('apartment', 'services', 'apartmentServices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        // Validazione dei dati in ingresso
        $validated = $request->validate([
            'title' => 'required|max:250',
            'img' => 'nullable|image', // L'immagine è opzionale ma deve essere un file di tipo immagine
            'address' => 'required|max:100',
            'rooms' => 'required|integer|min:1',
            'beds' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'mq' => 'required|integer|min:1',
            'is_available' => 'required|boolean',
            'services' => 'required|min:1|array|exists:services,id', // Verifica che ci sia almeno un servizio selezionato
        ]);

        // Trova l'appartamento da aggiornare
        $apartment = Apartment::findOrFail($id);

        // Verifica se l'indirizzo è stato modificato
        $addressChanged = $apartment->address !== $validated['address'];

        // Aggiorna i campi dell'appartamento
        $apartment->title = $validated['title'];
        $apartment->address = $validated['address'];
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

        // Se l'indirizzo è cambiato, aggiorna latitudine e longitudine tramite l'API TomTom
        if ($addressChanged) {
            try {
                // Chiamata API TomTom Geocode per aggiornare latitudine e longitudine
                $client = new Client(['verify' => false]);
                $apiKey = config('services.tomtom.key');
                $queryParams = [
                    'query' => urlencode($validated['address']),
                    'key' => $apiKey,
                ];
                $url = 'https://api.tomtom.com/search/2/geocode/' . $queryParams['query'] . '.json?view=Unified&key=' . $queryParams['key'];

                // Log dell'URL per debug
                Log::info('TomTom API URL: ' . $url);

                // Esecuzione della richiesta HTTP e decodifica del JSON di risposta
                $response = $client->get($url);
                $data = json_decode($response->getBody(), true);

                // Aggiorna latitudine e longitudine solo se l'indirizzo è valido
                if (isset($data['results'][0]['position'])) {
                    $apartment->latitude = $data['results'][0]['position']['lat'];
                    $apartment->longitude = $data['results'][0]['position']['lon'];
                } else {
                    // Se l'indirizzo non è stato trovato, ritorna un errore
                    return redirect()->back()->withErrors('Indirizzo non trovato');
                }
            } catch (\Exception $e) {
                // Log dell'errore e ritorno alla pagina precedente con un messaggio di errore
                Log::error('Error updating apartment coordinates: ' . $e->getMessage());
                return redirect()->back()->withErrors('Si è verificato un errore durante l\'aggiornamento delle coordinate.');
            }
        }

        // Salva le modifiche all'appartamento
        $apartment->save();

        // Associa i nuovi servizi all'appartamento
        if ($request->has('services')) {
            $apartment->services()->sync($request->input('services'));
        }

        // Reindirizza l'utente alla lista degli appartamenti con un messaggio di successo
        return redirect()->route('apartments.index')->with('success', 'Appartamento aggiornato con successo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Trova l'appartamento da eliminare
        $apartment = Apartment::findOrFail($id);

        // Elimina l'immagine associata se esiste
        if ($apartment->img) {
            Storage::disk('public')->delete($apartment->img);
        }

        // Elimina l'appartamento dal database
        $apartment->delete();

        // Reindirizza l'utente alla lista degli appartamenti con un messaggio di successo
        return redirect()->route('apartments.index')->with('success', 'Appartamento eliminato con successo');
    }
}
