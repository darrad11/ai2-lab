<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\Favorite;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    // Trasa dla listy przepisów
    #[Route('/recipes', name: 'recipe_list')]
    public function list(Request $request, RecipeRepository $recipeRepository, FavoriteRepository $favoriteRepository): Response
    {
        $search = $request->query->get('search');

        // Pobranie przepisów z bazy danych z posortowaniem po liczbie wyświetleń
        if ($search) {
            $recipes = $recipeRepository->findBySearch($search);
        } else {
            $recipes = $recipeRepository->findAll();
        }

        $isFavorite = false;

        if ($this->getUser()) {
            $user = $this->getUser();
            $favorite = $favoriteRepository->findOneBy(['user' => $user, 'recipe' => $recipes]);
            $isFavorite = $favorite !== null;
        }

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
            'search' => $search,
            'isFavorite' => $isFavorite,
        ]);
    }

    // Trasa dla filtracji przepisów
    #[Route('/filter', name: 'recipe_filter')]
    public function filter(Request $request, RecipeRepository $recipeRepository, FavoriteRepository $favoriteRepository): Response
    {
        $criteria = [
            'cuisine' => $request->query->get('cuisine') ?: null,
            'difficulty' => $request->query->get('difficulty') ?: null,
            'is_vegetarian' => $request->query->get('is_vegetarian') ?: null,
        ];

        // Dodanie składników do kryteriów
        $criteria['ingredients'] = [
            $request->query->get('ingredient1'),
            $request->query->get('ingredient2'),
            $request->query->get('ingredient3'),
        ];

        $search = $request->query->get('search');
        $ingredients = $request->query->get('ingredients'); // Dodanie składników do filtrów

        // Znalezienie przepisów na podstawie filtrów i wyszukiwania
        $recipes = $recipeRepository->findByFiltersAndSearch($criteria, $search, $ingredients);

        $isFavorite = false;

        if ($this->getUser()) {
            $user = $this->getUser();
            $favorite = $favoriteRepository->findOneBy(['user' => $user, 'recipe' => $recipes]);
            $isFavorite = $favorite !== null;
        }

        return $this->render('recipe/filter.html.twig', [
            'recipes' => $recipes,
            'search' => $search,
            'isFavorite' => $isFavorite,
        ]);
    }

    // Trasa dla szczegółów przepisu
    #[Route('/recipes/{id}', name: 'recipe_detail')]
    public function detail(int $id, RecipeRepository $recipeRepository, FavoriteRepository $favoriteRepository): Response
    {
        // Pobierz przepis z bazy danych na podstawie ID
        $recipe = $recipeRepository->find($id);

        // Jeśli przepis nie istnieje, wyświetl błąd
        if (!$recipe) {
            throw $this->createNotFoundException('Recipe not found');
        }

        $isFavorite = false;

        if ($this->getUser()) {
            $user = $this->getUser();
            $favorite = $favoriteRepository->findOneBy(['user' => $user, 'recipe' => $recipe]);
            $isFavorite = $favorite !== null;
        }

        // Zwiększ liczbę wyświetleń przepisu
        $recipe->setViews($recipe->getViews() + 1);

        // Renderowanie widoku z detalami przepisu
        return $this->render('recipe/detail.html.twig', [
            'recipe' => $recipe,
            'isFavorite' => $isFavorite,
        ]);
    }

    // Trasa dla dodawania do ulubionych
    #[Route('/recipes/{id}/favorite', name: 'recipe_favorite')]
    public function favorite(int $id, EntityManagerInterface $entityManager, RecipeRepository $recipeRepository, FavoriteRepository $favoriteRepository): Response
    {
        $recipe = $recipeRepository->find($id);
        if (!$recipe) {
            throw $this->createNotFoundException('Recipe not found');
        }

        // Sprawdzamy, czy użytkownik jest zalogowany
        if ($this->getUser()) {
            $user = $this->getUser();
            $favorite = $favoriteRepository->findOneBy(['user' => $user, 'recipe' => $recipe]);

            if ($favorite) {
                // Jeśli przepis jest już w ulubionych, usuwamy go
                $entityManager->remove($favorite);
                $this->addFlash('success', 'Przepis usunięty z ulubionych!');
            } else {
                // Dodajemy przepis do ulubionych
                $favorite = new Favorite();
                $favorite->setUser($user);
                $favorite->setRecipe($recipe);
                $entityManager->persist($favorite);
                $this->addFlash('success', 'Przepis dodany do ulubionych!');
            }

            $entityManager->flush();
        } else {
            $this->addFlash('error', 'Musisz być zalogowany, aby dodać przepis do ulubionych.');
        }

        return $this->redirectToRoute('recipe_detail', ['id' => $id]);
    }

    // Trasa dla dodawania nowego przepisu
    #[Route('/recipe/new', name: 'recipe_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Obsługa przesyłania zdjęcia
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                // Wygenerowanie unikalnej nazwy pliku
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    // Przenieś zdjęcie do folderu ./images/
                    $imageFile->move(
                        $this->getParameter('images_directory'), // Pobranie ścieżki z konfiguracji
                        $newFilename
                    );

                    // Ustawienie ścieżki do obrazu w encji
                    $recipe->setImage('images/' . $newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Wystąpił błąd podczas przesyłania zdjęcia.');
                    return $this->render('recipe/new.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
            }
            $ingredients = $form->get('ingredients')->getData();
            if ($ingredients) {
                $recipe->setIngredients($ingredients);
            }

            // Dodaj użytkownika (opcjonalnie, jeśli system logowania jest używany)
            if ($this->getUser()) {
                $recipe->setUser($this->getUser());
            }

            // Zapisz przepis w bazie danych
            $entityManager->persist($recipe);
            $entityManager->flush();

            $this->addFlash('success', 'Przepis został dodany!');

            return $this->redirectToRoute('recipe_list');
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
