<?php

namespace App\Entity;

use App\Repository\PreparationIngredientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PreparationIngredientRepository::class)]
class PreparationIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 3, nullable: true)]
    private ?string $weight = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0', nullable: true)]
    private ?string $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'preparationIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Preparation $preparation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredient $ingredient = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 3, nullable: true)]
    private ?string $volume = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): static
    {
        $this->weight = $weight;

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

    public function getPreparation(): ?Preparation
    {
        return $this->preparation;
    }

    public function setPreparation(?Preparation $preparation): static
    {
        $this->preparation = $preparation;

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

    public function __toString(){
        return (null == $this->getPreparation())?'':$this->getPreparation()->getName().' / '.$this->getIngredient()->getCode();
    }

    public function getCost(){
        $cost = 0;
        if (0 < $this->getQuantity()){
            $cost += $this->getQuantity()*$this->getIngredient()->getPrice();
        }elseif (0 < $this->getVolume()){
            $cost += $this->getVolume()*$this->getIngredient()->getPrice();
        }else{
            $cost += ($this->getWeight()/1000)*$this->getIngredient()->getPrice();
        };
        return $cost;
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
}
