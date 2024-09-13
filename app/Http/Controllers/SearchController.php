<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // Recupera i criteri di ricerca dalla query string
        $query = $request->input('query');
        $services = $request->input('services'); // Filtra per servizi
        $zone = $request->input('zone'); // Filtra per zona

        // Esegui la query per trovare gli appartamenti
        $apartments = Apartment::where('title', 'like', "%$query%")
            ->when($services, function ($q) use ($services) {
                return $q->whereHas('services', function ($q2) use ($services) {
                    $q2->whereIn('id', $services);
                });
            })
            ->when($zone, function ($q) use ($zone) {
                return $q->where('address', 'like', "%$zone%");
            })
            ->paginate(10); // Usa la paginazione per evitare di restituire troppi risultati in una volta

        // Restituisci i risultati in formato JSON
        return response()->json([
            'apartments' => $apartments
        ]);
    }
}
