<?php

namespace App\Tests\Service;

use Psr\Log\NullLogger;
use App\Dto\Output\WeatherData;
use App\Service\WeatherService;
use PHPUnit\Framework\TestCase;
use App\Exception\WeatherApiException;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\Exception\TransportException;

/**
 * @covers \App\Service\WeatherService
 *
 * Unit tests for WeatherService.
 */
class WeatherServiceTest extends TestCase
{

    /**
     * Tests that valid API response is correctly mapped to WeatherData DTO.
     */
    public function testGetWeatherDataReturnsValidDto()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('toArray')->willReturn([
            'location' => [
                'name' => 'London',
                'country' => 'United Kingdom'
            ],
            'current' => [
                'temp_c' => 18.2,
                'condition' => ['text' => 'Sunny'],
                'humidity' => 45,
                'wind_kph' => 15.3,
                'last_updated' => '2025-05-27 14:00',
            ],
        ]);

        $httpClient->method('request')->willReturn($response);

        $service = new WeatherService($httpClient, new NullLogger(), 'dummy-key', 'http://api.weatherapi.test');

        $weather = $service->getWeatherData('London');

        $this->assertInstanceOf(WeatherData::class, $weather);
        $this->assertEquals('London', $weather->city);
        $this->assertEquals('Sunny', $weather->condition);
    }

    /**
     * Tests that a WeatherApiException is thrown when API returns an error block.
     */
    public function testThrowsExceptionIfApiReturnsError()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('toArray')->willReturn([
            'error' => [
                'message' => 'City not found',
            ],
        ]);

        $httpClient->method('request')->willReturn($response);

        $service = new WeatherService($httpClient, new NullLogger(), 'dummy-key', 'http://api.weatherapi.test');

        $this->expectException(WeatherApiException::class);

        $service->getWeatherData('UnknownCity');
    }

    /**
     * Tests that a WeatherApiException is thrown when a transport-level error occurs.
     */
    public function testThrowsExceptionOnTransportFailure()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willThrowException(
            new TransportException('Connection failed')
        );

        $service = new WeatherService($httpClient, new NullLogger(), 'dummy-key', 'http://api.weatherapi.test');

        $this->expectException(WeatherApiException::class);

        $service->getWeatherData('Kyiv');
    }
}
