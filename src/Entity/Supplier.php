<?php

namespace App\Entity;

use App\Repository\SupplierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplierRepository::class)]
class Supplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'Supplier', targetEntity: SupplierPrice::class)]
    private Collection $supplierPrices;

    public function __construct()
    {
        $this->supplierPrices = new ArrayCollection();
    }

    public function __toString()
    {
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

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTime $updated_at): static
    {
        $this->updated_at = $updated_at;

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
            $supplierPrice->setSupplier($this);
        }

        return $this;
    }

    public function removeSupplierPrice(SupplierPrice $supplierPrice): static
    {
        if ($this->supplierPrices->removeElement($supplierPrice)) {
            // set the owning side to null (unless already changed)
            if ($supplierPrice->getSupplier() === $this) {
                $supplierPrice->setSupplier(null);
            }
        }

        return $this;
    }
}
