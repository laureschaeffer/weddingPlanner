<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    /**
     * @var Collection<int, Creation>
     */
    #[ORM\OneToMany(targetEntity: Creation::class, mappedBy: 'category')]
    private Collection $creations;

    public function __construct()
    {
        $this->creations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Creation>
     */
    public function getCreations(): Collection
    {
        return $this->creations;
    }

    public function addCreation(Creation $creation): static
    {
        if (!$this->creations->contains($creation)) {
            $this->creations->add($creation);
            $creation->setCategory($this);
        }

        return $this;
    }

    public function removeCreation(Creation $creation): static
    {
        if ($this->creations->removeElement($creation)) {
            // set the owning side to null (unless already changed)
            if ($creation->getCategory() === $this) {
                $creation->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->title;
    }
}
