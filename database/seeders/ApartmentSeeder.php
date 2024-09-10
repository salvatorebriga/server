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

        // Truncate la tabella 'services'
        DB::table('apartments')->truncate();
        $apartments = [
            [
                'user_id' => 1,
                'title' => 'Luxury Penthouse in Manhattan',
                'img' => 'https://source.unsplash.com/1600x900/?luxury,house',
                'address' => '123 Park Ave, New York, NY',
                'latitude' => 40.7624,
                'longitude' => -73.9738,
                'rooms' => 5,
                'beds' => 4,
                'bathrooms' => 3,
                'mq' => 250,
                'is_avaible' => true,
            ],
            [
                'user_id' => 2,
                'title' => 'Modern Studio in Brooklyn',
                'img' => 'https://source.unsplash.com/1600x900/?studio,house',
                'address' => '456 Flatbush Ave, Brooklyn, NY',
                'latitude' => 40.661,
                'longitude' => -73.9626,
                'rooms' => 1,
                'beds' => 1,
                'bathrooms' => 1,
                'mq' => 45,
                'is_avaible' => true,
            ],
            [
                'user_id' => 3,
                'title' => 'Spacious Family Home in Queens',
                'img' => 'https://source.unsplash.com/1600x900/?family,house',
                'address' => '789 Queens Blvd, Queens, NY',
                'latitude' => 40.7342,
                'longitude' => -73.8702,
                'rooms' => 4,
                'beds' => 3,
                'bathrooms' => 2,
                'mq' => 180,
                'is_avaible' => false,
            ],
            [
                'user_id' => 4,
                'title' => 'Cozy Apartment near Central Park',
                'img' => 'https://source.unsplash.com/1600x900/?cozy,apartment',
                'address' => '321 W 59th St, New York, NY',
                'latitude' => 40.769,
                'longitude' => -73.9834,
                'rooms' => 2,
                'beds' => 2,
                'bathrooms' => 1,
                'mq' => 85,
                'is_avaible' => true,
            ],
            [
                'user_id' => 5,
                'title' => 'Stylish Loft in Soho',
                'img' => 'https://source.unsplash.com/1600x900/?loft,house',
                'address' => '210 Greene St, New York, NY',
                'latitude' => 40.7253,
                'longitude' => -74.0018,
                'rooms' => 3,
                'beds' => 2,
                'bathrooms' => 2,
                'mq' => 120,
                'is_avaible' => true,
            ],
            [
                'user_id' => 6,
                'title' => 'Elegant Apartment in Chelsea',
                'img' => 'https://source.unsplash.com/1600x900/?elegant,apartment',
                'address' => '789 8th Ave, New York, NY',
                'latitude' => 40.7405,
                'longitude' => -73.9897,
                'rooms' => 3,
                'beds' => 2,
                'bathrooms' => 2,
                'mq' => 100,
                'is_avaible' => true,
            ],
            [
                'user_id' => 7,
                'title' => 'Spacious Loft in the Financial District',
                'img' => 'https://source.unsplash.com/1600x900/?spacious,loft',
                'address' => '11 Wall St, New York, NY',
                'latitude' => 40.7074,
                'longitude' => -74.0113,
                'rooms' => 4,
                'beds' => 3,
                'bathrooms' => 2,
                'mq' => 150,
                'is_avaible' => false,
            ],
            [
                'user_id' => 8,
                'title' => 'Charming Apartment in Greenwich Village',
                'img' => 'https://source.unsplash.com/1600x900/?charming,apartment',
                'address' => '123 Waverly Pl, New York, NY',
                'latitude' => 40.7309,
                'longitude' => -73.9973,
                'rooms' => 2,
                'beds' => 1,
                'bathrooms' => 1,
                'mq' => 70,
                'is_avaible' => true,
            ],
            [
                'user_id' => 9,
                'title' => 'Luxurious Condo in the Upper East Side',
                'img' => 'https://source.unsplash.com/1600x900/?luxurious,condo',
                'address' => '456 Madison Ave, New York, NY',
                'latitude' => 40.7736,
                'longitude' => -73.9566,
                'rooms' => 4,
                'beds' => 3,
                'bathrooms' => 3,
                'mq' => 200,
                'is_avaible' => true,
            ],
            [
                'user_id' => 10,
                'title' => 'Bright Studio in the Lower East Side',
                'img' => 'https://source.unsplash.com/1600x900/?bright,studio',
                'address' => '321 Orchard St, New York, NY',
                'latitude' => 40.7188,
                'longitude' => -73.9839,
                'rooms' => 1,
                'beds' => 1,
                'bathrooms' => 1,
                'mq' => 50,
                'is_avaible' => true,
            ],
            [
                'user_id' => 11,
                'title' => 'Modern Loft in the Meatpacking District',
                'img' => 'https://source.unsplash.com/1600x900/?modern,loft',
                'address' => '10th Ave, New York, NY',
                'latitude' => 40.7400,
                'longitude' => -74.0060,
                'rooms' => 3,
                'beds' => 2,
                'bathrooms' => 2,
                'mq' => 110,
                'is_avaible' => false,
            ],
            [
                'user_id' => 12,
                'title' => 'Cozy Studio in the East Village',
                'img' => 'https://source.unsplash.com/1600x900/?cozy,studio',
                'address' => '123 2nd Ave, New York, NY',
                'latitude' => 40.7272,
                'longitude' => -73.9818,
                'rooms' => 1,
                'beds' => 1,
                'bathrooms' => 1,
                'mq' => 55,
                'is_avaible' => true,
            ],
            [
                'user_id' => 13,
                'title' => 'Elegant Loft in the Bowery',
                'img' => 'https://source.unsplash.com/1600x900/?elegant,loft',
                'address' => '456 Bowery, New York, NY',
                'latitude' => 40.7231,
                'longitude' => -73.9920,
                'rooms' => 3,
                'beds' => 2,
                'bathrooms' => 2,
                'mq' => 125,
                'is_avaible' => true,
            ],
            [
                'user_id' => 14,
                'title' => 'Stylish Studio in the West Village',
                'img' => 'https://source.unsplash.com/1600x900/?stylish,studio',
                'address' => '789 Washington St, New York, NY',
                'latitude' => 40.7374,
                'longitude' => -74.0059,
                'rooms' => 1,
                'beds' => 1,
                'bathrooms' => 1,
                'mq' => 60,
                'is_avaible' => true,
            ],
            [
                'user_id' => 15,
                'title' => 'Luxurious Apartment in Midtown',
                'img' => 'https://source.unsplash.com/1600x900/?luxurious,apartment',
                'address' => '234 5th Ave, New York, NY',
                'latitude' => 40.7447,
                'longitude' => -73.9878,
                'rooms' => 4,
                'beds' => 3,
                'bathrooms' => 3,
                'mq' => 220,
                'is_avaible' => true,
            ],
            [
                'user_id' => 16,
                'title' => 'Charming Loft in the Flatiron District',
                'img' => 'https://source.unsplash.com/1600x900/?charming,loft',
                'address' => '45 W 22nd St, New York, NY',
                'latitude' => 40.7412,
                'longitude' => -73.9897,
                'rooms' => 2,
                'beds' => 2,
                'bathrooms' => 1,
                'mq' => 80,
                'is_avaible' => true,
            ],
            [
                'user_id' => 17,
                'title' => 'Modern Loft in Tribeca',
                'img' => 'https://source.unsplash.com/1600x900/?modern,loft',
                'address' => '100 Franklin St, New York, NY',
                'latitude' => 40.7168,
                'longitude' => -74.0105,
                'rooms' => 3,
                'beds' => 2,
                'bathrooms' => 2,
                'mq' => 140,
                'is_avaible' => true,
            ],
            [
                'user_id' => 18,
                'title' => 'Cozy Condo in the Upper West Side',
                'img' => 'https://source.unsplash.com/1600x900/?cozy,condo',
                'address' => '123 Riverside Dr, New York, NY',
                'latitude' => 40.7934,
                'longitude' => -73.9706,
                'rooms' => 2,
                'beds' => 2,
                'bathrooms' => 1,
                'mq' => 95,
                'is_avaible' => true,
            ],
            [
                'user_id' => 19,
                'title' => 'Spacious Apartment in Battery Park City',
                'img' => 'https://source.unsplash.com/1600x900/?spacious,apartment',
                'address' => '200 Liberty St, New York, NY',
                'latitude' => 40.7112,
                'longitude' => -74.0152,
                'rooms' => 4,
                'beds' => 3,
                'bathrooms' => 2,
                'mq' => 180,
                'is_avaible' => false,
            ],
            [
                'user_id' => 20,
                'title' => 'Elegant Studio in the Upper East Side',
                'img' => 'https://source.unsplash.com/1600x900/?elegant,studio',
                'address' => '450 E 72nd St, New York, NY',
                'latitude' => 40.7658,
                'longitude' => -73.9553,
                'rooms' => 1,
                'beds' => 1,
                'bathrooms' => 1,
                'mq' => 65,
                'is_avaible' => true,
            ],
        ];

        foreach ($apartments as $apartment) {
            Apartment::create($apartment);
        }

        Schema::enableForeignKeyConstraints();

    }
}