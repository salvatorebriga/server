<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    // Relazione many-to-many con Apartment tramite la tabella pivot apartment_sponsor
    public function apartments()
    {
        return $this->belongsToMany(Apartment::class, 'apartment_sponsor', 'sponsor_id', 'apartment_id')
            ->withPivot('start_date', 'end_date');
    }

    // Funzione per calcolare il tempo rimanente della sponsorizzazione
    public function remainingTime()
    {
        $now = Carbon::now();

        // Converti start_date e end_date in oggetti Carbon e imposta il timezone
        $startDate = Carbon::parse($this->pivot->start_date)->timezone(config('app.timezone'));
        $endDate = Carbon::parse($this->pivot->end_date)->timezone(config('app.timezone'));

        if ($now->isBetween($startDate, $endDate)) {
            // Se la sponsorizzazione è ancora attiva, calcola il tempo residuo in secondi
            return $endDate->diffInSeconds($now);
        }

        // Se la sponsorizzazione è scaduta, restituisci 0
        return 0;
    }

    // Metodo per rimuovere sponsorizzazioni scadute automaticamente
    public static function boot()
    {
        parent::boot();

        // Evento per controllare e rimuovere le sponsorizzazioni scadute quando il modello viene recuperato
        static::retrieved(function ($sponsor) {
            $now = Carbon::now();
            $endDate = Carbon::parse($sponsor->pivot->end_date);

            // Se la sponsorizzazione è scaduta, elimina la relazione dal pivot table
            if ($now->greaterThan($endDate)) {
                $sponsor->apartments()->detach($sponsor->id);
            }
        });
    }
}
