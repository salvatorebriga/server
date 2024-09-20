<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TomTomMapContreller extends Controller
{
    public function mapData(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');

        $apiKey = env('TOMTOM_API_KEY');

        $params = [
            'key' => $apiKey,
            'zoom' => 18,
            'center' => "{$lng},{$lat}",
            'format' => 'jpg',
            'layer' => 'basic',
            'style' => 'main',
            'width' => 1920,
            'height' => 1080,
            'view' => 'Unified',
            'language' => 'en-GB',

        ];


        $urlMap = "https://api.tomtom.com/map/1/staticimage?" . http_build_query($params);
        return response()->json(['url' => $urlMap]);;
    }
}
