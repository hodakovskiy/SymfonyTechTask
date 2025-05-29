<?php

namespace App\Dto\Output;

/**
 * Data Transfer Object for weather information.
 *
 * This class represents the weather data for a specific location,
 * including current conditions and meteorological measurements.
 */
class WeatherData
{
    /** @var string The name of the city */
    public string $city;

    /** @var string The name of the country */
    public string $country;

    /** @var float The temperature in degrees Celsius */
    public float $temperature;

    /** @var string Textual description of weather conditions */
    public string $condition;

    /** @var int Relative humidity percentage (0-100) */
    public int $humidity;

    /** @var float Wind speed in kilometers per hour */
    public float $windSpeed;

    /** @var string Timestamp of when the data was last updated */
    public string $lastUpdated;

    /**
     * @param string $city The name of the city
     * @param string $country The name of the country
     * @param float $temperature Temperature in degrees Celsius
     * @param string $condition Textual description of weather conditions
     * @param int $humidity Relative humidity percentage (0-100)
     * @param float $windSpeed Wind speed in kilometers per hour
     * @param string $lastUpdated Timestamp of when the data was last updated
     */
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
