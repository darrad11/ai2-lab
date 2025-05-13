<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    // Metoda wyszukiwania po frazie
    public function findBySearch(string $search): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.name LIKE :search OR r.description LIKE :search OR r.ingredients LIKE :search') // Wyszukiwanie po nazwie i opisielub składnikach
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }

    // Metoda do filtrów (można ją już mieć)
    public function findByFiltersAndSearch(array $criteria, ?string $search, ?string $ingredients = null): array
    {
        $qb = $this->createQueryBuilder('r');

        // Dodajemy warunki filtrów
        if ($criteria['cuisine']) {
            $qb->andWhere('r.cuisine = :cuisine')
                ->setParameter('cuisine', $criteria['cuisine']);
        }
        if ($criteria['difficulty']) {
            $qb->andWhere('r.difficulty = :difficulty')
                ->setParameter('difficulty', $criteria['difficulty']);
        }
    // Filtrowanie według 'is_vegetarian' (jeśli podano)
        if ($criteria['is_vegetarian'] !== null) {
            $qb->andWhere('r.is_vegetarian = :is_vegetarian')
                ->setParameter('is_vegetarian', $criteria['is_vegetarian']);
        }

        // Dodajemy warunek wyszukiwania
        if ($search) {
            $qb->andWhere('r.name LIKE :search OR r.description LIKE :search OR r.ingredients LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        // Dodajemy warunki wyszukiwania po składnikach
        if (!empty($criteria['ingredients'])) {
            foreach ($criteria['ingredients'] as $index => $ingredient) {
                if ($ingredient) {
                    $qb->andWhere("r.ingredients LIKE :ingredient$index")
                        ->setParameter("ingredient$index", '%' . $ingredient . '%');
                }
            }
        }



        return $qb->getQuery()->getResult();
    }
}