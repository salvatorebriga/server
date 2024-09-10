<?php

namespace Database\Seeders;

use App\Models\Sponsor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('sponsors')->truncate();
       $sponsors = [
            [
                "type" => "Basic",  // Tipo di sponsor, ad esempio "Basic", "Premium", "Exclusive"
                "price" => 2.99,    // Prezzo dello sponsor in unità monetarie
                "time" => 86400    // Durata dello sponsor in secondi, 24 ore
            ],
            [
                "type" => "Premium",
                "price" => 5.99,
                "time" => 259200 // 72 ore
            ],
            [
                "type" => "Exclusive",
                "price" => 9.99,
                "time" => 518400 // 144 ore
            ],
        ];

        foreach($sponsors as $sponsor){
            Sponsor::create($sponsor);
        }

        Schema::enableForeignKeyConstraints();
    }
}
