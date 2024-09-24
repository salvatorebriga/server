<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;


    public function apartments()
    {
        return $this->belongsToMany(Apartment::class, 'apartment_sponsor', 'sponsor_id', 'apartment_id')
            ->withPivot('start_date', 'end_date');
    }

    public function remainingTime()
    {
        // Ottieni il tempo residuo della sponsorizzazione
        $now = Carbon::now();

        // Converti start_date e end_date in oggetti Carbon
        $startDate = Carbon::parse($this->pivot->start_date);
        $endDate = Carbon::parse($this->pivot->end_date);

        if ($now->isBetween($startDate, $endDate)) {
            // Calcola la differenza in secondi se è ancora attivo
            return $endDate->diffInSeconds($now);
        }

        // Se la sponsorizzazione è scaduta, restituisci 0
        return 0;
    }
}
