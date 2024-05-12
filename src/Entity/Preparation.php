<?php

namespace App\Entity;

use App\Repository\PreparationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PreparationRepository::class)]
class Preparation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 3, nullable: true)]
    private ?string $weight = null;

    #[ORM\OneToMany(mappedBy: 'preparation', targetEntity: PreparationIngredient::class, orphanRemoval: true, cascade: ['persist'] )]
    private Collection $preparationIngredients;

    #[ORM\OneToMany(mappedBy: 'preparation', targetEntity: RecipeComposition::class, orphanRemoval: true)]
    private Collection $recipeCompositions;

    #[ORM\ManyToOne(inversedBy: 'preparations')]
    private ?Company $company = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $deleted_at = null;

    public function __construct()
    {
        $this->preparationIngredients = new ArrayCollection();
        $this->recipeCompositions = new ArrayCollection();
    }

    public function __toString(){
        return $this->getName();
    }

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

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return Collection<int, PreparationIngredient>
     */
    public function getPreparationIngredients(): Collection
    {
        return $this->preparationIngredients;
    }

    public function addPreparationIngredient(PreparationIngredient $preparationIngredient): static
    {
        if (!$this->preparationIngredients->contains($preparationIngredient)) {
            $this->preparationIngredients->add($preparationIngredient);
            $preparationIngredient->setPreparation($this);
        }

        return $this;
    }

    public function removePreparationIngredient(PreparationIngredient $preparationIngredient): static
    {
        if ($this->preparationIngredients->removeElement($preparationIngredient)) {
            // set the owning side to null (unless already changed)
            if ($preparationIngredient->getPreparation() === $this) {
                $preparationIngredient->setPreparation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecipeComposition>
     */
    public function getRecipeCompositions(): Collection
    {
        return $this->recipeCompositions;
    }

    public function addRecipeComposition(RecipeComposition $recipeComposition): static
    {
        if (!$this->recipeCompositions->contains($recipeComposition)) {
            $this->recipeCompositions->add($recipeComposition);
            $recipeComposition->setPreparation($this);
        }

        return $this;
    }

    public function removeRecipeComposition(RecipeComposition $recipeComposition): static
    {
        if ($this->recipeCompositions->removeElement($recipeComposition)) {
            // set the owning side to null (unless already changed)
            if ($recipeComposition->getPreparation() === $this) {
                $recipeComposition->setPreparation(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function isDeletable(): bool
    {
        return $this->getRecipeCompositions()->isEmpty();
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTime $deleted_at): static
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    public function getCostBase(){
        $cost = 0;
        foreach($this->getPreparationIngredients() as $pi){
            $cost += $pi->getCost();
        }
        return $cost; 
    }
}
