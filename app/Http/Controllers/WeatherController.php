<?php

namespace App\Http\Controllers;

use App\Services\SolarRadiationService;  // Import SolarRadiationService
use App\Services\SolarAzimuthService;  // Import SolarAzimuthService
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    protected $solarRadiationService;
    protected $solarAzimuthService;

    // Inject SolarRadiationService and SolarAzimuthService into the controller
    public function __construct(SolarRadiationService $solarRadiationService, SolarAzimuthService $solarAzimuthService)
    {
        $this->solarRadiationService = $solarRadiationService;
        $this->solarAzimuthService = $solarAzimuthService;
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
        $timezoneOffset = $data['timezone'];  // Timezone offset in seconds
        $temperature = $data['main']['temp'];  // Temperature (needed for the table)
        $humidity = $data['main']['humidity'];  // Humidity (needed for the table)
        $windSpeed = $data['wind']['speed'];  // Wind Speed (needed for the table)

        // Call the SolarRadiationService to calculate solar radiation
        $solarRadiation = $this->solarRadiationService->calculateSolarRadiation($latitude, $cloudCover, $timestamp, $timezoneOffset);
        
        // Call the SolarAzimuthService to calculate solar azimuth
        $solarAzimuth = $this->solarAzimuthService->calculateSolarAzimuth($latitude, $timestamp, $timezoneOffset);

        // Add solar radiation and azimuth to the weather data
        $data['solar_radiation'] = $solarRadiation;
        $data['solar_azimuth'] = $solarAzimuth;

        // Return the weather data with solar radiation and azimuth included
        return response()->json($data);
    }
}
