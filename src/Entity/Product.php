<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 3, nullable: true)]
    private ?string $piece_price = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, nullable: true)]
    private ?string $piece_quantity = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Company $company = null;

    #[ORM\ManyToOne]
    private ?Recipe $Recipe = null;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    private Collection $tags;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, nullable: true)]
    private ?string $stock = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, nullable: true)]
    private ?string $minimum_stock = null;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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

    public function getFullName(): ?string
    {
        $label = $this->name;
        if(null !== $this->piece_quantity){
            $label .= sprintf(' (x%s)', number_format($this->piece_quantity,0));
        }
        return $label;
    }

    public function getPiecePrice(): ?string
    {
        return $this->piece_price;
    }

    public function setPiecePrice(?string $piece_price): static
    {
        $this->piece_price = $piece_price;

        return $this;
    }

    public function getPieceQuantity(): ?string
    {
        return $this->piece_quantity;
    }

    public function setPieceQuantity(string $piece_quantity): static
    {
        $this->piece_quantity = $piece_quantity;

        return $this;
    }

    public function __toString(){
        return $this->getName();
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

    public function getRecipe(): ?Recipe
    {
        return $this->Recipe;
    }

    public function setRecipe(?Recipe $Recipe): static
    {
        $this->Recipe = $Recipe;

        return $this;
    }

    public function getAllergens(): ?array
    {
        $productAllergens = [];
        
        foreach($this->getIngredients() as $ingredient){
            $allergen = $ingredient->getAllergen();
            if(null !== $allergen){
                $productAllergens[$allergen->getName()] = true;
            }
        }
    

        return $productAllergens;
    }

    public function getIngredients(): ?array
    {
        $productIngredients = [];
        if ($this->getRecipe()){
            foreach($this->getRecipe()->getRecipeCompositions() as $composition){
                foreach($composition->getPreparation()->getPreparationIngredients() as $preparationIngredient){
                    $ingredient = $preparationIngredient->getIngredient();
                    if(null !== $ingredient){
                        $productIngredients[$ingredient->getCode()] = $ingredient;
                    }
                }
            }

        }

        return $productIngredients;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getStock(): ?string
    {
        return $this->stock;
    }

    public function setStock(?string $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getMinimumStock(): ?string
    {
        return $this->minimum_stock;
    }

    public function setMinimumStock(?string $minimum_stock): static
    {
        $this->minimum_stock = $minimum_stock;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImage(EmbeddedFile $image): void
    {
        $this->image = $image;
    }

    public function getImage(): ?EmbeddedFile
    {
        return $this->image;
    }
}
