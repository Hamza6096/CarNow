<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'car')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\ManyToOne(targetEntity: Energy::class, inversedBy: 'car')]
    #[ORM\JoinColumn(nullable: false)]
    private $energy;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Note::class, orphanRemoval: true)]
    private $notes;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Renting::class, orphanRemoval: true)]
    private $rentings;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    private $owner;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->rentings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getEnergy(): ?Energy
    {
        return $this->energy;
    }

    public function setEnergy(?Energy $energy): self
    {
        $this->energy = $energy;

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setCar($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getCar() === $this) {
                $note->setCar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Renting>
     */
    public function getRentings(): Collection
    {
        return $this->rentings;
    }

    public function addRenting(Renting $renting): self
    {
        if (!$this->rentings->contains($renting)) {
            $this->rentings[] = $renting;
            $renting->setCar($this);
        }

        return $this;
    }

    public function removeRenting(Renting $renting): self
    {
        if ($this->rentings->removeElement($renting)) {
            // set the owning side to null (unless already changed)
            if ($renting->getCar() === $this) {
                $renting->setCar(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
