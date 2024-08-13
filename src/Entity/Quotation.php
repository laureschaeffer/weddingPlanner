<?php

namespace App\Entity;

use App\Repository\QuotationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuotationRepository::class)]
class Quotation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $quotationNumber = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isAccepted = null;

    #[ORM\ManyToOne(inversedBy: 'quotations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    public function __construct()
    {
        //initialise
        $timezone = new \DateTimeZone('Europe/Paris');
        $dateAjd = new \DateTime('now', $timezone);
        $this->dateCreation = \DateTime::createFromInterface($dateAjd);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuotationNumber(): ?string
    {
        return $this->quotationNumber;
    }

    public function setQuotationNumber(string $quotationNumber): static
    {
        $this->quotationNumber = $quotationNumber;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function isAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setAccepted(bool $isAccepted): static
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function __toString()
    {
        return $this->quotationNumber;
    }
}
