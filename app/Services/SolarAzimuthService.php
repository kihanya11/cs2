<?php

namespace App\Services;

use Carbon\Carbon;

class SolarAzimuthService
{
    public function calculateSolarAzimuth(float $lat, int $timestamp, int $timezoneOffset): float
    {
        // Convert timestamp to Carbon object for easy handling
        $utcTime = Carbon::createFromTimestamp($timestamp, 'UTC');
        $localTime = $utcTime->addSeconds($timezoneOffset);
        $n = $localTime->dayOfYear;  // Day of the year
        
        // Step 1: Calculate Solar Declination
        $declination = 23.45 * sin(deg2rad(360 * (284 + $n) / 365));
        
        // Step 2: Calculate Local Solar Time
        $solarTime = $localTime->hour + ($localTime->minute / 60);  // Adjusted for local time
        $hourAngle = 15 * ($solarTime - 12);  // Solar hour angle in degrees

        // Step 3: Calculate Solar Zenith Angle
        $zenithAngle = acos(
            sin(deg2rad($lat)) * sin(deg2rad($declination)) +
            cos(deg2rad($lat)) * cos(deg2rad($declination)) * cos(deg2rad($hourAngle))
        );

        // Log intermediate results for debugging
        \Log::info('Solar Position Calculation', [
            'latitude' => $lat,
            'timestamp' => $timestamp,
            'timezone_offset' => $timezoneOffset,
            'local_time' => $localTime->toDateTimeString(),
            'day_of_year' => $n,
            'declination' => $declination,
            'hour_angle' => $hourAngle,
            'zenith_angle_deg' => rad2deg($zenithAngle),
        ]);

        // Step 4: Calculate Solar Azimuth Angle
        $sinAzimuth = (-sin(deg2rad($hourAngle)));
        $cosAzimuth = (
            cos(deg2rad($hourAngle)) * sin(deg2rad($lat)) -
            tan(deg2rad($declination)) * cos(deg2rad($lat))
        );

        $azimuth = rad2deg(atan2($sinAzimuth, $cosAzimuth));

        // Adjust azimuth to be between 0° and 360°
        if ($azimuth < 0) {
            $azimuth += 360;
        }

        // Log azimuth result for debugging
        \Log::info('Solar Azimuth Calculation', [
            'azimuth_raw' => $azimuth,
            'sin_azimuth' => $sinAzimuth,
            'cos_azimuth' => $cosAzimuth,
        ]);

        // Return azimuth angle
        return $azimuth;
    }
}
