<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $delivery_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $event_date = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderLine::class, orphanRemoval: true, cascade: ['persist'] )]
    private Collection $orderLines;

    #[ORM\Column]
    private ?bool $archived = null;

    #[ORM\Column]
    private ?bool $done = null;

    #[ORM\OneToMany(mappedBy: 'relativeOrder', targetEntity: Noncompliance::class)]
    private Collection $noncompliance;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Company $company = null;

    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
        $this->noncompliance = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(?\DateTimeInterface $delivery_date): static
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->event_date;
    }

    public function setEventDate(?\DateTimeInterface $event_date): static
    {
        $this->event_date = $event_date;

        return $this;
    }

    /**
     * @return Collection<int, OrderLine>
     */
    public function getOrderLines(): Collection
    {
        return $this->orderLines;
    }

    public function addOrderLine(OrderLine $orderLine): static
    {
        if (!$this->orderLines->contains($orderLine)) {
            $this->orderLines->add($orderLine);
            $orderLine->setOrderId($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): static
    {
        if ($this->orderLines->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getOrderId() === $this) {
                $orderLine->setOrderId(null);
            }
        }

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): static
    {
        $this->archived = $archived;

        return $this;
    }

    public function isDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(bool $done): static
    {
        $this->done = $done;

        return $this;
    }

    /**
     * @return Collection<int, Noncompliance>
     */
    public function getNoncompliance(): Collection
    {
        return $this->noncompliance;
    }

    public function addNoncompliance(Noncompliance $noncompliance): static
    {
        if (!$this->noncompliance->contains($noncompliance)) {
            $this->noncompliance->add($noncompliance);
            $noncompliance->setRelativeOrder($this);
        }

        return $this;
    }

    public function removeNoncompliance(Noncompliance $noncompliance): static
    {
        if ($this->noncompliance->removeElement($noncompliance)) {
            // set the owning side to null (unless already changed)
            if ($noncompliance->getRelativeOrder() === $this) {
                $noncompliance->setRelativeOrder(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return sprintf('[%s] %s (Livraison %s)', $this->getId(), $this->getCustomer()->getName(), $this->getDeliveryDate()->format('d/m/Y'));
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
}
