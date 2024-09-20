<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Sponsor;

class SponsorshipController extends Controller
{
    public function store(Request $request)
    {
        // Validazione

        $request->validate([
            'sponsorship' => 'required|in:basic,premium,exclusive',
            'apartment_id' => 'required|exists:apartments,id'
        ]);
        
        

        // Recupero appartamento
        $apartment = Apartment::find($request->apartment_id);
        // Recupero sponsorizzazione in base alla scelta
        $sponsor = Sponsor::where('type', $request->sponsorship)->first();
        
        // Controlla se esiste già una sponsorizzazione
        $existingSponsor = $apartment->sponsor()->where('sponsor_id', $sponsor->id)->first();
        dd($request->all());

        if ($existingSponsor) {
            // Se esiste, accumula il tempo
            $existingSponsor->update([
                'end_date' => $existingSponsor->end_date->addHours($sponsor->time)
            ]);
        } else {
            // Se non esiste, crea una nuova sponsorizzazione
            $apartment->sponsor()->attach($sponsor->id, [
                'start_date' => now(),
                'end_date' => now()->addHours($sponsor->time),
            ]);
        }



        return redirect()->back()->with('success', 'Apartment sponsored successfully');
    }
}
