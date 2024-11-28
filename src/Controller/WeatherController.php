<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{id}', name: 'app_weather', requirements: ['id' => '\d+'])]
    public function city(Location $location, MeteoDataRepository $repository): Response
    {
        $measurements = $repository->findByLocation($location);
        return $this->render('weather/city.html.twig', [
            //'controller_name' => 'WeatherController',
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
