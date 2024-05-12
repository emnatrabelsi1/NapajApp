<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: SupplierPrice::class, orphanRemoval: true, cascade: ['persist'] )]
    private Collection $supplierPrices;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: PreparationIngredient::class, orphanRemoval: true)]
    private Collection $preparationIngredients;

    #[ORM\ManyToOne]
    private ?MeasureUnit $measure_unit = null;

    #[ORM\ManyToOne]
    private ?Company $company = null;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    private ?IngredientCategory $IngredientCategory = null;

    #[ORM\ManyToOne]
    private ?Allergen $allergen = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $is_demo = null;

    #[ORM\Column]
    private ?bool $is_disabled = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $deleted_at = null;

    public function __construct()
    {
        $this->quantity = new ArrayCollection();
        $this->supplierPrices = new ArrayCollection();
        $this->preparationIngredients = new ArrayCollection();
    }

    public function __toString(){
        return $this->getCode();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, SupplierPrice>
     */
    public function getSupplierPrices(): Collection
    {
        return $this->supplierPrices;
    }

    public function addSupplierPrice(SupplierPrice $supplierPrice): static
    {
        if (!$this->supplierPrices->contains($supplierPrice)) {
            $this->supplierPrices->add($supplierPrice);
            $supplierPrice->setIngredient($this);
        }

        return $this;
    }

    public function removeSupplierPrice(SupplierPrice $supplierPrice): static
    {
        if ($this->supplierPrices->removeElement($supplierPrice)) {
            // set the owning side to null (unless already changed)
            if ($supplierPrice->getIngredient() === $this) {
                $supplierPrice->setIngredient(null);
            }
        }

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
            $preparationIngredient->setIngredient($this);
        }

        return $this;
    }

    public function removePreparationIngredient(PreparationIngredient $preparationIngredient): static
    {
        if ($this->preparationIngredients->removeElement($preparationIngredient)) {
            // set the owning side to null (unless already changed)
            if ($preparationIngredient->getIngredient() === $this) {
                $preparationIngredient->setIngredient(null);
            }
        }

        return $this;
    }
    
    public function getMeasureUnit(): ?MeasureUnit
    {
        return $this->measure_unit;
    }

    public function setMeasureUnit(?MeasureUnit $measure_unit): static
    {
        $this->measure_unit = $measure_unit;

        return $this;
    }

    public function getPrice():  ?string
    {
        $min = 0;
        $max = 0;
        foreach($this->getSupplierPrices() as $sp){
            $min = $sp->getPrice() < $min ? $sp->getPrice() : $min;
            $max = $sp->getPrice() > $max ? $sp->getPrice() : $max;
        }
        return $max;
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

    public function getIngredientCategory(): ?IngredientCategory
    {
        return $this->IngredientCategory;
    }

    public function setIngredientCategory(?IngredientCategory $IngredientCategory): static
    {
        $this->IngredientCategory = $IngredientCategory;

        return $this;
    }

    public function getAllergen(): ?Allergen
    {
        return $this->allergen;
    }

    public function setAllergen(?Allergen $allergen): static
    {
        $this->allergen = $allergen;

        return $this;
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

    public function isDeletable(): bool
    {
        if($this->getPreparationIngredients()->isEmpty()){
            return true;
        }

        return false;
    }

    public function isIsDemo(): ?bool
    {
        return $this->is_demo;
    }

    public function setIsDemo(bool $is_demo): static
    {
        $this->is_demo = $is_demo;

        return $this;
    }

    public function isIsDisabled(): ?bool
    {
        return $this->is_disabled;
    }

    public function setIsDisabled(bool $is_disabled): static
    {
        $this->is_disabled = $is_disabled;

        return $this;
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
}
