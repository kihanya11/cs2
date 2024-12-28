<?php

namespace App\Http\Controllers;

use App\Services\SolarRadiationService;  // Import SolarRadiationService
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    protected $solarRadiationService;

    // Inject SolarRadiationService into the controller
    public function __construct(SolarRadiationService $solarRadiationService)
    {
        $this->solarRadiationService = $solarRadiationService;
    }

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

        // Extract necessary data from the OpenWeather response
        $data = $response->json();
        $cloudCover = $data['clouds']['all'];  // Cloud cover percentage
        $timestamp = $data['dt'];  // Unix timestamp
        $temperature = $data['main']['temp'];  // Temperature (needed for the table)
        $humidity = $data['main']['humidity'];  // Humidity (needed for the table)
        $windSpeed = $data['wind']['speed'];  // Wind Speed (needed for the table)

        // Call the SolarRadiationService to calculate solar radiation
        $solarRadiation = $this->solarRadiationService->calculateSolarRadiation($latitude, $cloudCover, $timestamp);
        
        // Add solar radiation to the weather data
        $data['solar_radiation'] = $solarRadiation;

        // Return the weather data with solar radiation included
        return response()->json($data);
    }
}
