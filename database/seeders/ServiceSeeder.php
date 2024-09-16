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
        // Truncate the 'services' table
        DB::table('services')->truncate();

        // Define the array of services
        $services = [
            ['name' => 'Wi-Fi'],
            ['name' => 'Air Conditioning'],
            ['name' => 'Heating'],
            ['name' => 'Full Kitchen'],
            ['name' => 'Washing Machine'],
            ['name' => 'Dryer'],
            ['name' => 'Cable TV'],
            ['name' => 'Swimming Pool'],
            ['name' => 'Parking'],
            ['name' => 'Gym'],
            ['name' => 'Sauna'],
            ['name' => 'Jacuzzi'],
            ['name' => 'Balcony'],
            ['name' => 'Terrace'],
            ['name' => 'BBQ Grill'],
            ['name' => 'Cleaning Service'],
            ['name' => '24/7 Security'],
            ['name' => 'Wheelchair Accessible'],
            ['name' => 'Pet Friendly'],
            ['name' => 'Baby Crib'],
        ];

        // Insert the services into the table
        foreach ($services as $service) {
            Service::create($service);
        }

        Schema::enableForeignKeyConstraints();
    }
}
