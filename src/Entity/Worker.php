<?php

namespace App\Entity;

use App\Repository\WorkerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkerRepository::class)]
class Worker
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $urlPicture = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $altPicture = null;

    #[ORM\ManyToOne(inversedBy: 'workers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Job $job = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUrlPicture(): ?string
    {
        return $this->urlPicture;
    }

    public function setUrlPicture(?string $urlPicture): static
    {
        $this->urlPicture = $urlPicture;

        return $this;
    }

    public function getAltPicture(): ?string
    {
        return $this->altPicture;
    }

    public function setAltPicture(?string $altPicture): static
    {
        $this->altPicture = $altPicture;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function __toString(){
        return $this->name;
    }
}
