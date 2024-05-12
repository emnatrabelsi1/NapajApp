<?php

namespace App\Entity;

use App\Repository\RecipeCompositionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeCompositionRepository::class)]
class RecipeComposition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'recipeCompositions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 3, nullable: true)]
    private ?string $weight = null;

    #[ORM\ManyToOne(inversedBy: 'recipeCompositions')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Preparation $preparation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: '0', nullable: true)]
    private ?string $quantity = null;

    #[ORM\ManyToOne]
    private ?Ingredient $ingredient = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 3, nullable: true)]
    private ?string $volume = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getPreparation(): ?Preparation
    {
        return $this->preparation;
    }

    public function setPreparation(?Preparation $preparation): static
    {
        $this->preparation = $preparation;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(?string $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): static
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getVolume(): ?string
    {
        return $this->volume;
    }

    public function setVolume(?string $volume): static
    {
        $this->volume = $volume;

        return $this;
    }

    public function getCost(){
        if($this->getPreparation()){
            $costBase = $this->getPreparation()->getCostBase();
            
        }else{
            $costBase = $this->getIngredient()->getPrice();
        }
        
        if ($this->getQuantity() > 0){
            return $this->getQuantity()*$costBase;
        }elseif($this->getWeight() > 0){
            return ($this->getWeight()/1000)*$costBase/($this->getPreparation()->getWeight()/1000);
        }else{
            return $this->getVolume()*$costBase;
        }
    }
}
