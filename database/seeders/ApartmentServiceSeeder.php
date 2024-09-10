<?php

namespace Database\Seeders;

use App\Models\ApartmentService;
use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApartmentServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        // Truncate la tabella 'ApartmentService'
        DB::table('apartment_service')->truncate();

        for ($i = 0; $i < 30; $i++) {
            $new_apartment_service = new ApartmentService();

            $new_apartment_service->apartment_id = Apartment::inRandomOrder()->first()->id;
            $new_apartment_service->service_id = Service::inRandomOrder()->first()->id;

            $new_apartment_service->save();
        }

        Schema::enableForeignKeyConstraints();
    }
}
