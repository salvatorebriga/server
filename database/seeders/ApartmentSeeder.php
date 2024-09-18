<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apartment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApartmentSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        // Truncate la tabella 'apartments'
        DB::table('apartments')->truncate();
        $apartments = [
            [
                'user_id' => 1,
                'title' => 'Luxury Penthouse in Manhattan',
                'img' => 'images/NiEQf42Lvx7DOU6mQ2J3D23HvGr1YdC2VLLSsDuB.jpg',
                'address' => '123 Park Ave, New York, NY',
                'latitude' => 40.7624,
                'longitude' => -73.9738,
                'rooms' => 5,
                'beds' => 4,
                'bathrooms' => 3,
                'mq' => 250,
                'is_available' => true,
            ],
            [
                'user_id' => 2,
                'title' => 'Modern Studio in Brooklyn',
                'img' => 'images/NiEQf42Lvx7DOU6mQ2J3D23HvGr1YdC2VLLSsDuB.jpg',
                'address' => '456 Flatbush Ave, Brooklyn, NY',
                'latitude' => 40.661,
                'longitude' => -73.9626,
                'rooms' => 1,
                'beds' => 1,
                'bathrooms' => 1,
                'mq' => 45,
                'is_available' => true,
            ],
            [
                'user_id' => 3,
                'title' => 'Spacious Family Home in Queens',
                'img' => 'images/NiEQf42Lvx7DOU6mQ2J3D23HvGr1YdC2VLLSsDuB.jpg',
                'address' => '789 Queens Blvd, Queens, NY',
                'latitude' => 40.7342,
                'longitude' => -73.8702,
                'rooms' => 4,
                'beds' => 3,
                'bathrooms' => 2,
                'mq' => 180,
                'is_available' => false,

            ],
        ];

        foreach ($apartments as $apartment) {
            Apartment::create($apartment);
        }

        Schema::enableForeignKeyConstraints();
    }
}
