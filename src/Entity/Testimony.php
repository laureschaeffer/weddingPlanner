<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TestimonyRepository;

#[ORM\Entity(repositoryClass: TestimonyRepository::class)]
class Testimony
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $coupleName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPublished = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateReceipt = null;

    public function __construct()
    {
        //initialise
        $timezone = new \DateTimeZone('Europe/Paris');
        $dateAjd = new \DateTime('now', $timezone);
        $this->dateReceipt = \DateTime::createFromInterface($dateAjd);
        $this->setPublished(false);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoupleName(): ?string
    {
        return $this->coupleName;
    }

    public function setCoupleName(string $coupleName): static
    {
        $this->coupleName = $coupleName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setPublished(?bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    
    public function getDateReceipt(): ?\DateTimeInterface
    {
        return $this->dateReceipt;
    }
    
    public function setDateReceipt(\DateTimeInterface $dateReceipt): static
    {
        $this->dateReceipt = $dateReceipt;
        
        return $this;
    }

    public function __toString(){
        return $this->coupleName;
    }
}
