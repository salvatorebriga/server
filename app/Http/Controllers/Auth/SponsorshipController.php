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

        // Verifica se esiste uno sponsor con il tipo richiesto
        if ($sponsor) {
            // Converti i secondi in ore
            $hours = $sponsor->time / 3600;

            // Associa la sponsorizzazione all'appartamento
            $apartment->sponsors()->attach($sponsor->id, [
                'start_date' => now(),
                'end_date' => now()->addHours($hours),
            ]);

            return redirect()->back()->with('success', 'Apartment sponsored successfully');
        } else {
            // Gestisci il caso in cui lo sponsor non venga trovato
            return redirect()->back()->with('error', 'Sponsor not found');
        }
    }
}
