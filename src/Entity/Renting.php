<?php

namespace App\Entity;

use App\Repository\RentingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RentingRepository::class)]
class Renting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Car::class, inversedBy: 'rentings')]
    #[ORM\JoinColumn(nullable: false)]
    private $car;

    #[ORM\Column(type: 'datetime')]
    private $start;

    #[ORM\Column(type: 'datetime')]
    private $end;

    #[ORM\Column(type: 'boolean')]
    private $rentValidate;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $paymentDate;

    #[ORM\Column(type: 'decimal', precision: 6, scale: 2)]
    private $amout;

    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    private $typeOfPayment;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $plannedDuration;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $actualDuration;

    #[ORM\Column(type: 'decimal', precision: 6, scale: 2)]
    private $dailyPrice;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'rentings')]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function isRentValidate(): ?bool
    {
        return $this->rentValidate;
    }

    public function setRentValidate(bool $rentValidate): self
    {
        $this->rentValidate = $rentValidate;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeImmutable
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(?\DateTimeImmutable $paymentDate): self
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getAmout(): ?string
    {
        return $this->amout;
    }

    public function setAmout(string $amout): self
    {
        $this->amout = $amout;

        return $this;
    }

    public function getTypeOfPayment(): ?string
    {
        return $this->typeOfPayment;
    }

    public function setTypeOfPayment(?string $typeOfPayment): self
    {
        $this->typeOfPayment = $typeOfPayment;

        return $this;
    }

    public function getPlannedDuration(): ?\DateTimeInterface
    {
        return $this->plannedDuration;
    }

    public function setPlannedDuration(?\DateTimeInterface $plannedDuration): self
    {
        $this->plannedDuration = $plannedDuration;

        return $this;
    }

    public function getActualDuration(): ?\DateTimeInterface
    {
        return $this->actualDuration;
    }

    public function setActualDuration(?\DateTimeInterface $actualDuration): self
    {
        $this->actualDuration = $actualDuration;

        return $this;
    }

    public function getDailyPrice(): ?string
    {
        return $this->dailyPrice;
    }

    public function setDailyPrice(string $dailyPrice): self
    {
        $this->dailyPrice = $dailyPrice;

        return $this;
    }
}
