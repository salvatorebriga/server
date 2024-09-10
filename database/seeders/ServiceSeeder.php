<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        // Truncate la tabella 'services'
        DB::table('services')->truncate();

         // Definisci l'array di servizi
         $services = [
            ['name' => 'Wi-Fi'],
            ['name' => 'Aria Condizionata'],
            ['name' => 'Riscaldamento'],
            ['name' => 'Cucina Completa'],
            ['name' => 'Lavatrice'],
            ['name' => 'Asciugatrice'],
            ['name' => 'TV via Cavo'],
            ['name' => 'Piscina'],
            ['name' => 'Parcheggio'],
            ['name' => 'Palestra'],
            ['name' => 'Sauna'],
            ['name' => 'Jacuzzi'],
            ['name' => 'Balcone'],
            ['name' => 'Terrazzo'],
            ['name' => 'Barbecue'],
            ['name' => 'Servizio di Pulizia'],
            ['name' => 'Sicurezza 24/7'],
            ['name' => 'Accesso Disabili'],
            ['name' => 'Animali Ammessi'],
            ['name' => 'Culla per Bambini']
        ];
        
        // Inserisci i servizi nella tabella
        foreach ($services as $service) {
            Service::create($service);
        }

        Schema::enableForeignKeyConstraints();
    }
}
