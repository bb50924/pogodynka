<?php

namespace App\Controller;

use App\Entity\Forecast;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        WeatherUtil $util,
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('city')] string $city,
        #[MapQueryParameter('format')] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,

    ): Response
    {
        $forecasts = $util->getWeatherForCountryAndCity($country, $city);

        if ($format === 'csv') {
            if ($twig) {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'forecasts' => $forecasts,
                ]);
            } else {
                $csv = "city,country,date,temperature,fahrenheit,humidity,pressure,weather\n";
                $csv .= implode(
                    "\n",
                    array_map(fn(Forecast $f) => sprintf(
                        "%s,%s,%s,%s,%s,%s,%s,%s",
                        $city,
                        $country,
                        $f->getDate()->format('Y-m-d'),
                        $f->getTemperature(),
                        $f->getFahrenheit(),
                        $f->getHumidity(),
                        $f->getPressure(),
                        $f->getWeather()
                    ), $forecasts)
                );

                return new Response($csv, 200, [
                    'Content-Type' => 'text/plain',
                ]);
            }
        }
            if ($twig) {
                return $this->render('weather_api/index.json.twig', [
                    'city' => $city,
                    'country' => $country,
                    'forecasts' => $forecasts,
                ]);
            } else {
                return $this->json([
                    'city' => $city,
                    'country' => $country,
                    'forecasts' => array_map(fn(Forecast $f) => [
                        'date' => $f->getDate()->format('Y-m-d'),
                        'temperature' => $f->getTemperature(),
                        'fahrenheit' => $f->getFahrenheit(),
                        'humidity' => $f->getHumidity(),
                        'pressure' => $f->getPressure(),
                        'weather' => $f->getWeather(),
                    ], $forecasts),
                ]);
            }
        }
    }