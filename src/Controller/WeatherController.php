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

        $meteoData = $meteoDataRepository->findByLocation($location);
        return $this->render('weather/city.html.twig', [
            //'controller_name' => 'WeatherController',
            'location' => $location,
            'meteoData' => $meteoData,
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
