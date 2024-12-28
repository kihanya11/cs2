<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    public function getWeather(Request $request)
    {
        // OpenWeatherMap API key (use env variable for security)
        $apiKey = env('OPENWEATHER_API_KEY');
        
        // Fetch the latitude and longitude from the request
        $latitude = $request->input('lat');
        $longitude = $request->input('lng');

        // Send a request to OpenWeatherMap API
        $response = Http::withoutVerifying()->get("https://api.openweathermap.org/data/2.5/weather", [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => $apiKey,
            'units' => 'metric',
        ]);

        // Log the response for debugging
        Log::info('OpenWeatherMap Response:', $response->json());

        // Return the response as is (Laravel will handle any issues)
        return response()->json($response->json());
    }
}
