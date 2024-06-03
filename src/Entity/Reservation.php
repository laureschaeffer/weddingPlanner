<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $referenceOrder = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOrder = null;

    #[ORM\Column]
    private ?float $totalPrice = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePicking = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPrepared = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPicked = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
    
    #[ORM\Column(length: 50)]
    private ?string $firstname = null;
    
    #[ORM\Column(length: 100)]
    private ?string $surname = null;
    
    #[ORM\Column(length: 50)]
    private ?string $telephone = null;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'reservation', cascade: ['persist'])]
    private Collection $bookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReferenceOrder(): ?string
    {
        return $this->referenceOrder;
    }

    public function setReferenceOrder(?string $referenceOrder): static
    {
        $this->referenceOrder = $referenceOrder;

        return $this;
    }

    public function getDateOrder(): ?\DateTimeInterface
    {
        return $this->dateOrder;
    }

    public function setDateOrder(?\DateTimeInterface $dateOrder): static
    {
        $this->dateOrder = $dateOrder;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getDatePicking(): ?\DateTimeInterface
    {
        return $this->datePicking;
    }

    public function setDatePicking(\DateTimeInterface $datePicking): static
    {
        $this->datePicking = $datePicking;

        return $this;
    }

    public function isPrepared(): ?bool
    {
        return $this->isPrepared;
    }

    public function setPrepared(bool $isPrepared): static
    {
        $this->isPrepared = $isPrepared;

        return $this;
    }

    public function isPicked(): ?bool
    {
        return $this->isPicked;
    }

    public function setPicked(?bool $isPicked): static
    {
        $this->isPicked = $isPicked;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setReservation($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getReservation() === $this) {
                $booking->setReservation(null);
            }
        }

        return $this;
    }


    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function __toString(){
        return $this->referenceOrder;
    }
}
