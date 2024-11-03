<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\MeteoDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

//#[Route('/weather/')]
class WeatherController extends AbstractController
{
    //country? znaczy opcjonalnie
    #[Route('/weather/{country}/{city}', name: 'app_weather')]
    public function city(
        LocationRepository $locationRepository,
        MeteoDataRepository $meteoDataRepository,
        string $city,
        string $country = null
    ): Response
    {
        $location = $locationRepository->findOneBy([
            'country' => $country,
            'city' => $city,
        ]);

        $dummyForecast = [
            [
                "date" => new \DateTime('2024-10-28'),
                "celsiusTemperature" => 18,
                "flcelsiusTemperature" => 16,
                "pressure" => 1000,
                "humidity" => 40,
                "wind_speed" => 3.4,
                "wind_direction" => 270,
                "cloudiness" => 70,
                'icon' => 'cloud',
            ],
            [
                "date" => new \DateTime('2024-10-29'),
                "celsiusTemperature" => 21,
                "flcelsiusTemperature" => 20,
                "pressure" => 1024,
                "humidity" => 16,
                "wind_speed" => 3.4,
                "wind_direction" => 270,
                "cloudiness" => 10,
                'icon' => 'sun',
            ],
            [
                "date" => new \DateTime('2024-10-30'),
                "celsiusTemperature" => 17,
                "flcelsiusTemperature" => 18,
                "pressure" => 1003,
                "humidity" => 59,
                "wind_speed" => 3.4,
                "wind_direction" => 270,
                "cloudiness" => 70,
                'icon' => 'cloud-rain',
            ],
        ];

        $meteoData = $meteoDataRepository->findByLocation($location);
        return $this->render('weather/city.html.twig', [
            //'controller_name' => 'WeatherController',
            'location' => $location,
            'meteoData' => $meteoData,
            'dummyForecast' =>$dummyForecast,
        ]);
    }

    /*#[Route('/weather/highlander-says')]
    public function highlanderSays(): Response
    {
        //int from 0 to 10
        $draw = random_int(0,100);

        $forecast = $draw < 50 ? "It's going to rain" : "It's going to be sunny";

        //return response
        return new Response(
            "<html><body>$forecast</body></html>"
        );

        //better response
        return $this->>render('weather/highlander_says.html.twig',[
            'forecast' => $forecast,
        ]);
    }*/
}
