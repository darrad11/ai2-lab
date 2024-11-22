<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:code-and-city',
    description: 'Add a short description for your command',
)]
class WeatherCodeAndCityCommand extends Command
{
    public LocationRepository $locationRepository;
    public WeatherUtil $weatherUtil;
    public function __construct(LocationRepository $locationRepository, WeatherUtil $weatherUtil)
    {
        $this->locationRepository = $locationRepository;
        $this->weatherUtil = $weatherUtil;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('weather:code-and-city')
            ->setDescription('Weather Forecast for Country Code and City')
            ->addArgument('code', InputArgument::REQUIRED, 'Country Code')
            ->addArgument('city', InputArgument::REQUIRED, 'City');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countryCode = $input->getArgument('code');
        $cityName = $input->getArgument('city');

        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $cityName
        ]);

        $measurements = $this->weatherUtil->getWeatherForLocation($location);
        $io->writeln(sprintf('Location: %s, %s', $location->getCity(), $location->getCountry()));
        foreach ($measurements as $measurement) {
            $io->writeln(sprintf("\t%s: %s °C",
                $measurement->getDate()->format('Y-m-d'),
                $measurement->getCelsiusTemperature()
            ));
        }

        return Command::SUCCESS;
    }
}
