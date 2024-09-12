<?php

namespace App\Models;

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
}
