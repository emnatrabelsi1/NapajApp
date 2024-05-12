<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address2 = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Order::class, orphanRemoval: true)]
    private Collection $orders;

    #[ORM\Column(length: 510, nullable: true)]
    private ?string $route = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'customers')]
    private Collection $subscribed_tags;

    #[ORM\Column]
    private ?bool $internal = false;
    // internal signifie interne à Napaj (Ex : le Labo)

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->subscribed_tags = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): static
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setCustomerId($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCustomerId() === $this) {
                $order->setCustomerId(null);
            }
        }

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getSubscribedTags(): Collection
    {
        return $this->subscribed_tags;
    }

    public function addSubscribedTag(Tag $subscribedTag): static
    {
        if (!$this->subscribed_tags->contains($subscribedTag)) {
            $this->subscribed_tags->add($subscribedTag);
        }

        return $this;
    }

    public function removeSubscribedTag(Tag $subscribedTag): static
    {
        $this->subscribed_tags->removeElement($subscribedTag);

        return $this;
    }

    public function isInternal(): ?bool
    {
        return $this->internal;
    }

    public function setInternal(bool $internal): static
    {
        $this->internal = $internal;

        return $this;
    }
}
