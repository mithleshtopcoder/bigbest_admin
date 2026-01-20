<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodingService
{
    public static function getLatLng(string $address): ?array
    {
        $response = Http::withHeaders([
            'User-Agent' => 'RGsmartorganic/1.0 (support@rstopcoder.com)'
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $address,
            'format' => 'json',
            'limit' => 1,
        ]);

        if ($response->successful() && !empty($response->json())) {
            return [
                'latitude'  => (float) $response->json()[0]['lat'],
                'longitude' => (float) $response->json()[0]['lon'],
            ];
        }

        return null;
    }
}