<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    public function autocomplete(Request $request)
    {
        $query = $request->get('query');

        if (strlen($query) < 3) {
            return response()->json([]);
        }


        $client = new Client(['verify' => false]);
        $apiKey = env('TOMTOM_API_KEY'); // Inserisci la tua chiave API nel file .env

        $response = $client->get("https://api.tomtom.com/search/2/search/{$query}.json", [
            'query' => [
                'key' => $apiKey,
                'typeahead' => 'true',
                'limit' => 10,
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return response()->json($data['results']);
    }
}
