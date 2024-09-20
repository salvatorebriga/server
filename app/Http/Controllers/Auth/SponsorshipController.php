<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Sponsor;

class SponsorshipController extends Controller
{
    // Metodo per gestire la creazione della sponsorizzazione
    public function store (Request $request)
    {
        //validazione
        $request->validate([
            'sposorship'=> 'required|in:basic,premium,exsclusive',
            'apartment_id'=> 'required|exists:apartments,id'
        ]);
        //rewcupero app dall id
        $apartment = Apartment::find($request->apartment_id);
        
        //rewcupero sposnorizzazione in base al alla scelta
        $sponsor = Sponsor::where('type', $request->sponsorship)->first();
    
        // associazione della spons all id dell appartamento
        $apartment->sponsor()->attach($sponsor->id, [
            'start_date'=> now(),
            'end_date'=> now()->addHours($sponsor->time),
        ]);

        return redirect()->back()->with('success', 'Apartment sponsored successfully');
    }
}
