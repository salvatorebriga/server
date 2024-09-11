<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
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
        //Recupero tutti gli appartamenti collegati all'utente loggato
        $apartments = Apartment::where('user_id', auth()->id())->get();

        //Passa gli appartamenti 
        return view('auth.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Form creazione
        return view('auth.apartments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            //Validazione dati
            $validated = $request->validate([
                'title' => 'required|max:250',
                'img' => 'nullable|image',
                'address' => 'required|max:100',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'rooms' => 'required|integer|min:1',
                'beds' => 'required|integer|min:1',
                'bathrooms' => 'required|integer|min:1',
                'mq' => 'required|integer|min:1',
                'is_avaible' => 'required|boolean',
            ]);

            //Creazuione nuovo appartamento
            $apartment = new Apartment();
            $apartment->user_id = auth()->id(); //Associa l appartamento all utente loggato
            $apartment->title = $validated['title'];
            $apartment->img = $request->file('img') ? $request->file('img')->store('images', 'public') : null;
            $apartment->address = $validated['address'];
            $apartment->latitude = $validated['latitude'];
            $apartment->longitude = $validated['longitude'];
            $apartment->rooms = $validated['rooms'];
            $apartment->beds = $validated['beds'];
            $apartment->bathrooms = $validated['bathrooms'];
            $apartment->mq = $validated['mq'];
            $apartment->is_avaible = $validated['is_avaible'] == '1'; // Converte '1' in true e '0' in false


            //Salvare nuovo appartamento

            $apartment->save();

            //Metodo sintetico con le fillable dichiarate nel model
            //Apartment::create($request->all()); Questa riga di codice convalida tutto il codice scritto dopo la validazione($valitated=$request->valitated[ecc..])

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
        //recuperare l appartamento dal db utilizzando l id
        $apartment = Apartment::findOrFail($id);

        //passiamo i dati allo show
        return view('auth.apartments.show', compact('apartment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        // Recupera l'appartamento dal database utilizzando l'ID
        $apartment = Apartment::findOrFail($id);

        // Passa i dati dell'appartamento alla vista 'edit'
        return view('auth.apartments.edit', compact('apartment'));
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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'rooms' => 'required|integer|min:1',
            'beds' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'mq' => 'required|integer|min:1',
            'is_avaible' => 'required|boolean',
        ]);

        // Trova l'appartamento da aggiornare
        $apartment = Apartment::findOrFail($id);

        // Aggiorna i dati dell'appartamento
        $apartment->title = $validated['title'];
        $apartment->address = $validated['address'];
        $apartment->latitude = $validated['latitude'];
        $apartment->longitude = $validated['longitude'];
        $apartment->rooms = $validated['rooms'];
        $apartment->beds = $validated['beds'];
        $apartment->bathrooms = $validated['bathrooms'];
        $apartment->mq = $validated['mq'];
        $apartment->is_avaible = $validated['is_avaible'];

        // Gestione dell'immagine
        if ($request->hasFile('img')) {
            // Elimina l'immagine esistente se presente
            if ($apartment->img) {
                Storage::disk('public')->delete($apartment->img);
            }
            // Salva la nuova immagine
            $apartment->img = $request->file('img')->store('images', 'public');
        }

        // Salva le modifiche
        $apartment->save();

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
