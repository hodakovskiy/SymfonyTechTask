<?php

namespace App\Service;

use App\Dto\WeatherData;
use App\Exception\WeatherApiException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * WeatherService handles fetching and parsing weather data from external API.
 */
class WeatherService
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface $logger,
        private readonly string $apiKey,
        private readonly string $apiUrl
    ) {}

    /**
     * Fetches weather data for a given city.
     *
     * @param string $city
     * @return WeatherData
     * @throws WeatherApiException on any failure
     */
    public function getWeatherData(string $city): WeatherData
    {
        $url = sprintf('%s?key=%s&q=%s', $this->apiUrl, $this->apiKey, urlencode($city));

        try {
            // Send request to weather API
            $response = $this->httpClient->request('GET', $url);

            // Validate HTTP status
            if ($response->getStatusCode() !== 200) {
                throw new WeatherApiException("Unexpected API status code: " . $response->getStatusCode());
            }

            // Decode response JSON
            try {
                $data = $response->toArray();
            } catch (\Throwable $e) {
                throw new WeatherApiException('Invalid API response format.');
            }

            // Handle API error message
            if (isset($data['error'])) {
                $message = $data['error']['message'] ?? 'Unknown API error.';
                throw new WeatherApiException($message);
            }

            // Map API data to DTO
            $weather = new WeatherData(
                city: $data['location']['name'],
                country: $data['location']['country'],
                temperature: $data['current']['temp_c'],
                condition: $data['current']['condition']['text'],
                humidity: $data['current']['humidity'],
                windSpeed: $data['current']['wind_kph'],
                lastUpdated: $data['current']['last_updated']
            );

            // Log success
            $this->logger->info("Weather in {$weather->city}: {$weather->temperature}°C, {$weather->condition}");

            return $weather;
        } catch (TransportExceptionInterface $e) {
            // Network/transport-level error
            $this->logger->error('Transport error: ' . $e->getMessage());
            throw new WeatherApiException('Connection issue with weather API.');
        } catch (WeatherApiException $e) {
            // Already handled above
            throw $e;
        } catch (\Throwable $e) {
            // Catch-all for any unexpected errors
            $this->logger->error('Unhandled exception: ' . $e->getMessage());
            throw new WeatherApiException('Failed to retrieve weather data.');
        }
    }
}
