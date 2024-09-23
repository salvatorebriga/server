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
            ['name' => 'Wi-Fi', 'icon' => 'fas fa-wifi'],
            ['name' => 'Air Conditioning', 'icon' => 'fas fa-wind'],
            ['name' => 'Heating', 'icon' => 'fas fa-fire'],
            ['name' => 'Full Kitchen', 'icon' => 'fas fa-utensils'],
            ['name' => 'Washing Machine', 'icon' => 'fas fa-tint'],
            ['name' => 'Dryer', 'icon' => 'fas fa-cloud'],
            ['name' => 'Cable TV', 'icon' => 'fas fa-tv'],
            ['name' => 'Swimming Pool', 'icon' => 'fas fa-swimming-pool'],
            ['name' => 'Parking', 'icon' => 'fas fa-parking'],
            ['name' => 'Gym', 'icon' => 'fas fa-dumbbell'],
            ['name' => 'Sauna', 'icon' => 'fas fa-hot-tub'],
            ['name' => 'Jacuzzi', 'icon' => 'fas fa-spa'],
            ['name' => 'Balcony', 'icon' => 'fas fa-archway'],
            ['name' => 'Terrace', 'icon' => 'fas fa-chair'],
            ['name' => 'BBQ Grill', 'icon' => 'fas fa-fire-alt'],
            ['name' => 'Cleaning Service', 'icon' => 'fas fa-broom'],
            ['name' => '24/7 Security', 'icon' => 'fas fa-shield-alt'],
            ['name' => 'Wheelchair Accessible', 'icon' => 'fas fa-wheelchair'],
            ['name' => 'Pet Friendly', 'icon' => 'fas fa-paw'],
            ['name' => 'Baby Crib', 'icon' => 'fas fa-baby-carriage'],
        ];

        // Insert the services into the table
        foreach ($services as $service) {
            Service::create($service);
        }

        Schema::enableForeignKeyConstraints();
    }
}
