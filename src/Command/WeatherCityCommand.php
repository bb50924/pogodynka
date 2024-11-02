<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Displays forecasts for city in country',
)]
class WeatherCityCommand extends Command
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly WeatherUtil $weatherUtil,
        string $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('country_code', InputArgument::REQUIRED, 'Country code')
            ->addArgument('city_name', InputArgument::REQUIRED, 'City name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countryCode = $input->getArgument('country_code');
        $cityName = $input->getArgument('city_name');

        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $cityName,
        ]);
        $forecasts = $this->weatherUtil->getWeatherForLocation($location);
        foreach ($forecasts as $forecast) {
            $io->section(sprintf('Forecast for %s on %s:', $location->getCity(), $forecast->getDate()->format('Y-m-d')));
            $io->text([
                'Temperature: ' . $forecast->getTemperature() . ' C',
                'Humidity: ' . $forecast->getHumidity() . '%',
                'Pressure: ' . $forecast->getPressure() . ' hPa',
                'Weather: ' . $forecast->getWeather(),
            ]);

        }

        return Command::SUCCESS;
    }
}