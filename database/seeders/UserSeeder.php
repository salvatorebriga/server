<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        // Truncate la tabella 'services'
        DB::table('users')->truncate();

        // Carica i dati dal file JSON
        $jsonData = file_get_contents(database_path('data/user.json'));
        $users = json_decode($jsonData, true);

        foreach ($users as $user) {
            // Cripta la password e crea l'utente
            User::create([
                'name' => $user['name'],
                'surname' => $user['surname'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'date_of_birth' => $user['date_of_birth']
            ]);
        }
        Schema::enableForeignKeyConstraints();
    }
}
