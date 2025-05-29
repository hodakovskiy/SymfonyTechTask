<?php

namespace App\Controller;

use App\Exception\WeatherApiException;
use App\Dto\Input\CitySearchDto;
use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class WeatherController extends AbstractController
{

    public function __construct(private readonly WeatherService $weatherService) {}

    /**
     * Weather search & display.
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/weather', name: 'weather_show', methods: ['GET', 'POST'])]
    public function show(Request $request, ValidatorInterface $validator): Response
    {
        $weather = null;
        $errors = [];



        if ($request->isMethod('POST')) {
            $data = CitySearchDto::fromRequest($request);

            $violations = $validator->validate($data);

            if (count($violations) > 0) {

                $errors = array_map(
                    fn($violation) => $violation->getMessage(),
                    iterator_to_array($violations)
                );
            } else {
                try {
                    $weather = $this->weatherService->getWeatherData($data->city);
                } catch (WeatherApiException $e) {
                    $errors[] = $e->getMessage();
                }
            }
        }

        return $this->render('weather/weather.html.twig', [
            'weather' => $weather,
            'errors' => $errors,
        ]);
    }
}
