<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_frame = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeComposition::class, orphanRemoval: true, cascade: ['persist'] )]
    private Collection $recipeCompositions;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeCutting::class, orphanRemoval: true, cascade: ['persist'] )]
    private Collection $recipeCuttings;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?Company $company = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $process = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, nullable: true)]
    private ?string $selling_price = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $deleted_at = null;

    public function __construct()
    {
        $this->recipeCompositions = new ArrayCollection();
        $this->recipeCuttings = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function isIsFrame(): ?bool
    {
        return $this->is_frame;
    }

    public function setIsFrame(?bool $is_frame): static
    {
        $this->is_frame = $is_frame;

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
            $recipeComposition->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeComposition(RecipeComposition $recipeComposition): static
    {
        if ($this->recipeCompositions->removeElement($recipeComposition)) {
            // set the owning side to null (unless already changed)
            if ($recipeComposition->getRecipe() === $this) {
                $recipeComposition->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecipeCutting>
     */
    public function getRecipeCuttings(): Collection
    {
        return $this->recipeCuttings;
    }

    public function addRecipeCutting(RecipeCutting $recipeCutting): static
    {
        if (!$this->recipeCuttings->contains($recipeCutting)) {
            $this->recipeCuttings->add($recipeCutting);
            $recipeCutting->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeCutting(RecipeCutting $recipeCutting): static
    {
        if ($this->recipeCuttings->removeElement($recipeCutting)) {
            // set the owning side to null (unless already changed)
            if ($recipeCutting->getRecipe() === $this) {
                $recipeCutting->setRecipe(null);
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function __toString(){
        return $this->getName();
    }

    public function getProcess(): ?string
    {
        return $this->process;
    }

    public function setProcess(?string $process): static
    {
        $this->process = $process;

        return $this;
    }

    public function getSellingPrice(): ?string
    {
        return $this->selling_price;
    }

    public function setSellingPrice(?string $selling_price): static
    {
        $this->selling_price = $selling_price;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addConstraint(new UniqueEntity([
            'fields' => ['company', 'code'],
            'errorPath' => 'code',
            'message' => 'Ce code est déjà utilisé',
        ]));
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
