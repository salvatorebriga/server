<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    // Relazione many-to-many con Sponsor tramite la tabella pivot apartment_sponsor
    public function sponsors()
    {
        return $this->belongsToMany(Sponsor::class, 'apartment_sponsor', 'apartment_id', 'sponsor_id')
            ->withPivot('start_date', 'end_date');
    }

    // Altre relazioni
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statistics()
    {
        return $this->hasMany(Statistic::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
}
