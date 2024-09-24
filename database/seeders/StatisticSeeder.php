<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apartment;
use App\Models\Statistic;
use Carbon\Carbon;

class StatisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apartments = Apartment::all();

        if ($apartments->isEmpty()) {
            $this->command->info('Nessun appartamento trovato nel database.');
            return;
        }

        $startDate = Carbon::now()->subDays(364);

        for ($day = 0; $day < 365; $day++) {
            $currentDate = $startDate->copy()->addDays($day);

            foreach ($apartments as $apartment) {
                $views = rand(1, 20);

                for ($i = 0; $i < $views; $i++) {
                    $ipAddress = $this->generateRandomIp();

                    Statistic::create([
                        'apartment_id' => $apartment->id,
                        'ip_address' => $ipAddress,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);
                }
            }
        }

        $this->command->info('Statistiche di visualizzazione generate con successo.');
    }

    /**
     * @return string
     */
    private function generateRandomIp()
    {
        return implode('.', array_map('intval', array_rand(range(0, 255), 4)));
    }
}
