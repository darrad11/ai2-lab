<?php

namespace App\Controller;

use App\Entity\MeteoData;
use App\Form\MeteoDataType;
use App\Repository\MeteoDataRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/meteo/data')]
final class MeteoDataController extends AbstractController
{
    #[Route(name: 'app_meteo_data_index', methods: ['GET'])]
    public function index(MeteoDataRepository $meteoDataRepository): Response
    {
        return $this->render('meteo_data/index.html.twig', [
            'meteo_datas' => $meteoDataRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_meteo_data_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $meteoDatum = new MeteoData();
        $form = $this->createForm(MeteoDataType::class, $meteoDatum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($meteoDatum);
            $entityManager->flush();

            return $this->redirectToRoute('app_meteo_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('meteo_data/new.html.twig', [
            'meteo_datum' => $meteoDatum,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_meteo_data_show', methods: ['GET'])]
    public function show(MeteoData $meteoDatum): Response
    {
        return $this->render('meteo_data/show.html.twig', [
            'meteo_datum' => $meteoDatum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_meteo_data_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MeteoData $meteoDatum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MeteoDataType::class, $meteoDatum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_meteo_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('meteo_data/edit.html.twig', [
            'meteo_datum' => $meteoDatum,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_meteo_data_delete', methods: ['POST'])]
    public function delete(Request $request, MeteoData $meteoDatum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$meteoDatum->getId(), $request->request->get('_token'))) {
            $entityManager->remove($meteoDatum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_meteo_data_index', [], Response::HTTP_SEE_OTHER);
    }
}
