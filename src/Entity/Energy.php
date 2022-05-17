<?php

namespace App\Entity;

use App\Repository\EnergyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnergyRepository::class)]
class Energy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(mappedBy: 'energy', targetEntity: Car::class)]
    private $car;

    #[ORM\Column(type: 'string', length: 45)]
    private $nameEnergy;

    public function __construct()
    {
        $this->car = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Car>
     */
    public function getCar(): Collection
    {
        return $this->car;
    }

    public function addCar(Car $car): self
    {
        if (!$this->car->contains($car)) {
            $this->car[] = $car;
            $car->setEnergy($this);
        }

        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->car->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getEnergy() === $this) {
                $car->setEnergy(null);
            }
        }

        return $this;
    }

    public function getNameEnergy(): ?string
    {
        return $this->nameEnergy;
    }

    public function setNameEnergy(string $nameEnergy): self
    {
        $this->nameEnergy = $nameEnergy;

        return $this;
    }
}
