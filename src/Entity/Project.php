<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $firstname = null;

    #[ORM\Column(length: 100)]
    private ?string $surname = null;

    #[ORM\Column(length: 170, nullable:true)]
    private ?string $email = null;

    #[ORM\Column(length: 60)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEvent = null;

    #[ORM\Column]
    private ?int $nbGuest = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateReceipt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isContacted = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?Destination $destination = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?Budget $budget = null;

    /**
     * @var Collection<int, Prestation>
     */
    #[ORM\ManyToMany(targetEntity: Prestation::class, inversedBy: 'projects')]
    private Collection $prestations;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'project', orphanRemoval: true)]
    #[ORM\OrderBy(["datePost" => "DESC"])] //rajout d'un order by à la collection
    private Collection $comments;

    #[ORM\Column(nullable: true)]
    private ?int $finalPrice = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: true)]
    // #[ORM\JoinColumn(nullable: false)]
    private ?State $state = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?User $user = null;

    /**
     * @var Collection<int, Quotation>
     */
    #[ORM\OneToMany(targetEntity: Quotation::class, mappedBy: 'project')]
    private Collection $quotations;

    public function __construct()
    {
        $this->prestations = new ArrayCollection();
        $this->comments = new ArrayCollection();
        //initialise
        $timezone = new \DateTimeZone('Europe/Paris');
        $this->dateReceipt = new \DateTime('now', $timezone);
        $this->isContacted = false;
        $this->quotations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTimeInterface $dateEvent): static
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getNbGuest(): ?int
    {
        return $this->nbGuest;
    }

    public function setNbGuest(int $nbGuest): static
    {
        $this->nbGuest = $nbGuest;

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

    public function getDateReceipt(): ?\DateTimeInterface
    {
        return $this->dateReceipt;
    }

    public function setDateReceipt(?\DateTimeInterface $dateReceipt): static
    {
        $this->dateReceipt = $dateReceipt;

        return $this;
    }

    public function isContacted(): ?bool
    {
        return $this->isContacted;
    }

    public function setContacted(?bool $isContacted): static
    {
        $this->isContacted = $isContacted;

        return $this;
    }

    public function getDestination(): ?Destination
    {
        return $this->destination;
    }

    public function setDestination(?Destination $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    public function setBudget(?Budget $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * @return Collection<int, Prestation>
     */
    public function getPrestations(): Collection
    {
        return $this->prestations;
    }

    public function addPrestation(Prestation $prestation): static
    {
        if (!$this->prestations->contains($prestation)) {
            $this->prestations->add($prestation);
        }

        return $this;
    }

    public function removePrestation(Prestation $prestation): static
    {
        $this->prestations->removeElement($prestation);

        return $this;
    }


    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setProject($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProject() === $this) {
                $comment->setProject(null);
            }
        }

        return $this;
    }

    public function getFinalPrice(): ?int
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(?int $finalPrice): static
    {
        $this->finalPrice = $finalPrice;

        return $this;
    }
    
    
    public function getState(): ?State
    {
        return $this->state;
    }
    
    public function setState(?State $state): static
    {
        $this->state = $state;
        
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
    
    public function __toString(){
        return $this->firstname . " " . $this->surname;
    }

    //verifie si la date est valide: pas dépassée
    public function isDateValid() : bool 
    {
        $timezone = new \DateTimeZone('Europe/Paris');
        $ajd = new \DateTime('now', $timezone);

        if($this->dateEvent < $ajd){
            return false; 
        } else {
            return true ;
        }
    }

    //verifie si certaines actions peuvent etre commises sur le projet: il doit etre seulement "en cours"
    public function isEditable() : bool
    {
        if($this->getState()->getId() === 1){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return Collection<int, Quotation>
     */
    public function getQuotations(): Collection
    {
        return $this->quotations;
    }

    public function addQuotation(Quotation $quotation): static
    {
        if (!$this->quotations->contains($quotation)) {
            $this->quotations->add($quotation);
            $quotation->setProject($this);
        }

        return $this;
    }

    public function removeQuotation(Quotation $quotation): static
    {
        if ($this->quotations->removeElement($quotation)) {
            // set the owning side to null (unless already changed)
            if ($quotation->getProject() === $this) {
                $quotation->setProject(null);
            }
        }

        return $this;
    }
}
