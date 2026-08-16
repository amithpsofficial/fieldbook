<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    public function getWeatherForLocation(string $location): ?array
    {
        if (empty($location)) {
            return null;
        }

        $cacheKey = 'weather_' . strtolower(trim($location));

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($location) {
            $apiKey = config('services.openweather.key');

            $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'q' => $location,
                'appid' => $apiKey,
                'units' => 'metric',
            ]);

            if ($response->failed()) {
                return null;
            }

            $data = $response->json();

            return [
                'location' => $data['name'] ?? $location,
                'temperature' => $data['main']['temp'] ?? null,
                'feels_like' => $data['main']['feels_like'] ?? null,
                'humidity' => $data['main']['humidity'] ?? null,
                'description' => $data['weather'][0]['description'] ?? null,
                'icon' => $data['weather'][0]['icon'] ?? null,
                'wind_speed' => $data['wind']['speed'] ?? null,
            ];
        });
    }
}