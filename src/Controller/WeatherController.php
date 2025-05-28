<?php

namespace App\Controller;

use App\Exception\WeatherApiException;
use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    public function __construct(private readonly WeatherService $weatherService) {}

    #[Route('/weather', name: 'weather', methods: ['GET', 'POST'])]
    public function weather(Request $request): Response
    {
        $weather = null;
        $error = null;

        if ($request->isMethod('POST')) {
            $city = trim($request->request->get('city'));

            if (empty($city)) {
                $error = 'Будь ласка, введіть назву міста.';
            } else {
                try {
                    $weather = $this->weatherService->getWeatherData($city);
                } catch (WeatherApiException $e) {
                    $error = $e->getMessage();
                }
            }
        }

        return $this->render('weather/weather.html.twig', [
            'weather' => $weather,
            'error' => $error,
        ]);
    }
}
