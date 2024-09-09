<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AppointmentRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'Veuillez sélectionner une date')]
    #[Assert\GreaterThanOrEqual(
        "tomorrow",
        message: "Veuillez choisir une date à partir de demain"
    )]
    // contrainte personalisée
    #[Assert\Callback([Appointment::class, "isDateNotSunday"])]
    private ?\DateTimeInterface $dateStart = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\Column(type: Types::TEXT)]
    
    private ?string $subject = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function __toString()
    {
        return $this->dateStart;
    }

    // -----------------------------------methodes-----------------------------------

    //vérifie que la date choisie n'est pas un dimanche
    //méthode statique pour que symfony puisse l'appeler sans avoir à instancier l'objet
    public static function isDateNotSunday($dateStart, ExecutionContextInterface $context)
    {
        if ($dateStart->format('w') == 0) {
            // 0 correspond à dimanche dans la méthode 'w' (numéro du jour de la semaine)
            $context->buildViolation('La date ne peut pas être un dimanche')
                ->atPath('dateStart')
                ->addViolation();
        }
    }

}
