<?php

namespace App\Services;

use Carbon\Carbon;

class SolarAzimuthService
{
    // Constants
    const G_SC = 1367; // Solar constant (W/m^2)

    public function calculateSolarAzimuth(float $lat, int $timestamp): float
    {
        // Convert timestamp to Carbon object for easy handling
        $date = Carbon::createFromTimestamp($timestamp);
        $n = $date->dayOfYear;  // Day of the year
        
        // Step 1: Calculate Solar Declination
        $declination = 23.45 * sin(deg2rad(360 * (284 + $n) / 365));
        
        // Step 2: Calculate Hour Angle
        $solarTime = $date->hour + ($date->minute / 60);
        $hourAngle = 15 * ($solarTime - 12);
        
        // Step 3: Calculate Solar Zenith Angle (already calculated earlier)
        $zenithAngle = acos(sin(deg2rad($lat)) * sin(deg2rad($declination)) + 
                            cos(deg2rad($lat)) * cos(deg2rad($declination)) * cos(deg2rad($hourAngle)));
        
        // Step 4: Calculate Solar Azimuth Angle
        // Azimuth formula
        $azimuth = rad2deg(atan2(-sin(deg2rad($hourAngle)), 
                                cos(deg2rad($hourAngle)) * sin(deg2rad($lat)) - tan(deg2rad($declination)) * cos(deg2rad($lat))));

        // Adjust azimuth angle to be between 0° and 360°
        if ($azimuth < 0) {
            $azimuth += 360;
        }
        
        return $azimuth;
    }
}
