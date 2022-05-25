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
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Car::class)]
    private $car;

    #[ORM\Column(type: 'string', length: 45)]
    private $nameCategory;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $slug;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categories')]
    private $parent;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private $categories;

    public function __construct()
    {
        $this->car = new ArrayCollection();
        $this->categories = new ArrayCollection();
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
            $car->setCategory($this);
        }

        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->car->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getCategory() === $this) {
                $car->setCategory(null);
            }
        }

        return $this;
    }

    public function getNameCategory(): ?string
    {
        return $this->nameCategory;
    }

    public function setNameCategory(string $nameCategory): self
    {
        $this->nameCategory = $nameCategory;

        return $this;
    }

    public function __toString()
    {
        return $this->nameCategory;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(self $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setParent($this);
        }

        return $this;
    }

    public function removeCategory(self $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getParent() === $this) {
                $category->setParent(null);
            }
        }

        return $this;
    }
}
