<?php

namespace App\Dto;

class WeatherData
{
    public string $city;
    public string $country;
    public float $temperature;
    public string $condition;
    public int $humidity;
    public float $windSpeed;
    public string $lastUpdated;

    public function __construct(
        string $city,
        string $country,
        float $temperature,
        string $condition,
        int $humidity,
        float $windSpeed,
        string $lastUpdated
    ) {
        $this->city = $city;
        $this->country = $country;
        $this->temperature = $temperature;
        $this->condition = $condition;
        $this->humidity = $humidity;
        $this->windSpeed = $windSpeed;
        $this->lastUpdated = $lastUpdated;
    }
}
