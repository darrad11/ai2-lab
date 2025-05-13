<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $instructions = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $cuisine = null;

    #[ORM\Column(type: Types::STRING, length: 50)]
    #[Assert\Choice(choices: ['beginner', 'intermediate', 'advanced', 'masterchef'], message: 'Choose a valid difficulty level.')]
    private ?string $difficulty = null;

    #[ORM\Column(type: "boolean")]
    private ?bool $is_vegetarian = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $image = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ingredients = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $views = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(string $instructions): static
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function getCuisine(): ?string
    {
        return $this->cuisine;
    }

    public function setCuisine(string $cuisine): static
    {
        $this->cuisine = $cuisine;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function isVegetarian(): ?bool
    {
        return $this->is_vegetarian;
    }
    
    public function setIsVegetarian(bool $is_vegetarian): static
    {
        $this->is_vegetarian = $is_vegetarian;
    
        return $this;
    }    

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;

    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(?string $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): static
    {
        $this->views = $views;
        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Favorite", mappedBy="recipe")
     */
    private $favorites;

    // Getter dla $favorites
    public function getFavorites(): ?array
    {
        return $this->favorites;
    }

    // Setter dla $favorites
    public function setFavorites(array $favorites): self
    {
        $this->favorites = $favorites;
        return $this;
    }
}
