<?php

namespace App\Entity;

use App\Repository\NoncomplianceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoncomplianceRepository::class)]
class Noncompliance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?NoncomplianceType $noncomplianceType = null;

    #[ORM\ManyToOne(inversedBy: 'declarant')]
    private ?Order $relativeOrder = null;

    #[ORM\Column(length: 255)]
    private ?string $declarant = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $declaration_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $processing_date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $processing_comment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $assigned = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?NoncomplianceState $noncomplianceState = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoncomplianceType(): ?NoncomplianceType
    {
        return $this->noncomplianceType;
    }

    public function setNoncomplianceType(?NoncomplianceType $noncomplianceType): static
    {
        $this->noncomplianceType = $noncomplianceType;

        return $this;
    }

    public function getRelativeOrder(): ?Order
    {
        return $this->relativeOrder;
    }

    public function setRelativeOrder(?Order $relativeOrder): static
    {
        $this->relativeOrder = $relativeOrder;

        return $this;
    }

    public function getDeclarant(): ?string
    {
        return $this->declarant;
    }

    public function setDeclarant(string $declarant): static
    {
        $this->declarant = $declarant;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDeclarationDate(): ?\DateTimeInterface
    {
        return $this->declaration_date;
    }

    public function setDeclarationDate(\DateTimeInterface $declaration_date): static
    {
        $this->declaration_date = $declaration_date;

        return $this;
    }

    public function getProcessingDate(): ?\DateTimeInterface
    {
        return $this->processing_date;
    }

    public function setProcessingDate(?\DateTimeInterface $processing_date): static
    {
        $this->processing_date = $processing_date;

        return $this;
    }

    public function getProcessingComment(): ?string
    {
        return $this->processing_comment;
    }

    public function setProcessingComment(?string $processing_comment): static
    {
        $this->processing_comment = $processing_comment;

        return $this;
    }

    public function getAssigned(): ?string
    {
        return $this->assigned;
    }

    public function setAssigned(?string $assigned): static
    {
        $this->assigned = $assigned;

        return $this;
    }

    public function getNoncomplianceState(): ?NoncomplianceState
    {
        return $this->noncomplianceState;
    }

    public function setNoncomplianceState(?NoncomplianceState $noncomplianceState): static
    {
        $this->noncomplianceState = $noncomplianceState;

        return $this;
    }
}
