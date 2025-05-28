<?php

namespace App\Service;

use App\Dto\WeatherData;
use App\Exception\WeatherApiException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface $logger,
        private readonly string $apiKey,
        private readonly string $apiUrl
    ) {}

    /**
     * @throws WeatherApiException
     */
    public function getWeatherData(string $city): WeatherData
    {
        $url = sprintf('%s?key=%s&q=%s', $this->apiUrl, $this->apiKey, urlencode($city));

        try {
            $response = $this->httpClient->request('GET', $url);

            if ($response->getStatusCode() !== 200) {
                throw new WeatherApiException("API повернув некоректний статус: " . $response->getStatusCode());
            }

            $data = $response->toArray();


            if (isset($data['error'])) {
                $message = $data['error']['message'] ?? 'Невідома помилка';
                throw new WeatherApiException($message);
            }

            $weather = new WeatherData(
                city: $data['location']['name'],
                country: $data['location']['country'],
                temperature: $data['current']['temp_c'],
                condition: $data['current']['condition']['text'],
                humidity: $data['current']['humidity'],
                windSpeed: $data['current']['wind_kph'],
                lastUpdated: $data['current']['last_updated']
            );

            $this->logger->info("Погода в {$weather->city}: {$weather->temperature}°C, {$weather->condition}");

            return $weather;
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Помилка з’єднання з API: ' . $e->getMessage());
            throw new WeatherApiException('Проблема з’єднання з погодним API.');
        } catch (\Exception $e) {
            $this->logger->error('Загальна помилка: ' . $e->getMessage());
            throw new WeatherApiException('Помилка при отриманні погодних даних.');
        }
    }
}
