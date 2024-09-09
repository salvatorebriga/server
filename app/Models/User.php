<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Campi che possono essere assegnati in massa
    protected $fillable = [
        'name',
        'surname',
        'email',
        'date_of_birth',
        'password',
    ];

    // Campi che devono essere nascosti nelle risposte JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cast automatico per il tipo di dato
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
    ];
}
