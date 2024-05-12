<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Recipe::class)]
    private Collection $recipes;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: User::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Preparation::class)]
    private Collection $preparations;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Product::class)]
    private Collection $products;

    #[ORM\Column]
    private ?bool $is_napaj = null;

    #[ORM\Column]
    private ?bool $feature_recipe = false;

    #[ORM\Column]
    private ?bool $feature_product = false;

    #[ORM\Column]
    private ?bool $feature_order = false;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    private ?Customer $relative_customer = null;

    #[ORM\Column]
    private ?bool $manage_cutting = false;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->preparations = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setCompany($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getCompany() === $this) {
                $recipe->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection<int, Preparation>
     */
    public function getPreparations(): Collection
    {
        return $this->preparations;
    }

    public function addPreparation(Preparation $preparation): static
    {
        if (!$this->preparations->contains($preparation)) {
            $this->preparations->add($preparation);
            $preparation->setCompany($this);
        }

        return $this;
    }

    public function removePreparation(Preparation $preparation): static
    {
        if ($this->preparations->removeElement($preparation)) {
            // set the owning side to null (unless already changed)
            if ($preparation->getCompany() === $this) {
                $preparation->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCompany($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCompany() === $this) {
                $product->setCompany(null);
            }
        }

        return $this;
    }

    public function isNapaj(): ?bool
    {
        return $this->is_napaj;
    }

    public function setIsNapaj(bool $is_napaj): static
    {
        $this->is_napaj = $is_napaj;

        return $this;
    }

    public function isFeatureRecipe(): ?bool
    {
        return $this->feature_recipe;
    }

    public function setFeatureRecipe(bool $feature_recipe): static
    {
        $this->feature_recipe = $feature_recipe;

        return $this;
    }

    public function isFeatureProduct(): ?bool
    {
        return $this->feature_product;
    }

    public function setFeatureProduct(bool $feature_product): static
    {
        $this->feature_product = $feature_product;

        return $this;
    }

    public function isFeatureOrder(): ?bool
    {
        return $this->feature_order;
    }

    public function setFeatureOrder(bool $feature_order): static
    {
        $this->feature_order = $feature_order;

        return $this;
    }

    public function getRelativeCustomer(): ?Customer
    {
        return $this->relative_customer;
    }

    public function setRelativeCustomer(?Customer $relative_customer): static
    {
        $this->relative_customer = $relative_customer;

        return $this;
    }

    public function isManageCutting(): ?bool
    {
        return $this->manage_cutting;
    }

    public function setManageCutting(bool $manage_cutting): static
    {
        $this->manage_cutting = $manage_cutting;

        return $this;
    }
}
