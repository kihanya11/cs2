<?php

namespace App\Services;

use Carbon\Carbon;

class SolarRadiationService
{
    // Constants
    const G_SC = 1367; // Solar constant (W/m^2)
    
    public function calculateSolarRadiation(float $lat, float $cloudCover, int $timestamp): float
    {
        // Convert timestamp to Carbon object for easy handling
        $date = Carbon::createFromTimestamp($timestamp);
        $n = $date->dayOfYear;  // Day of the year
        
        // Step 1: Calculate Solar Declination
        $declination = 23.45 * sin(deg2rad(360 * (284 + $n) / 365));
        
        // Step 2: Calculate Hour Angle
        $solarTime = $date->hour + ($date->minute / 60);
        
        // Adjust for night time (solar time outside 6 AM - 6 PM)
        if ($solarTime < 6 || $solarTime > 18) {
            // Return zero radiation if the sun is not up (night time)
            return 0.0;
        }
        
        $hourAngle = 15 * ($solarTime - 12);
        
        // Step 3: Calculate Solar Zenith Angle
        $zenithAngle = acos(sin(deg2rad($lat)) * sin(deg2rad($declination)) + 
                            cos(deg2rad($lat)) * cos(deg2rad($declination)) * cos(deg2rad($hourAngle)));
        
        // Convert Zenith Angle to Degrees
        $zenithAngleDeg = rad2deg($zenithAngle);
        
        // Step 4: Calculate Air Mass Ratio
        $airMass = 1 / (cos(deg2rad($zenithAngleDeg)) + 0.50572 * (96.07995 - $zenithAngleDeg) ** -1.6364);
        
        // Step 5: Calculate Clear-Sky Radiation (Extraterrestrial Solar Radiation)
        $G_ext = self::G_SC * (1 + 0.033 * cos(2 * pi() * $n / 365));
        $tau = 0.75;  // Atmospheric transmittance (assumed for clear sky)
        $G_clr = $G_ext * $tau;
        
        // Step 6: Adjust for Cloud Cover
        $G = $G_clr * (1 - 0.75 * ($cloudCover / 100));
        
        return $G;
    }
}
