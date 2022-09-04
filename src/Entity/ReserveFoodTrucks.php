<?php

namespace App\Entity;

use App\Repository\ReserveFoodTrucksRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @ORM\Entity(repositoryClass=ReserveFoodTrucksRepository::class)
 */
class ReserveFoodTrucks
{

    public const MAX_RESERVATION_PER_DAY = 7;
    public const MAX_RESERVATION_FRIDAY = 6;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $foodTruckName;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $reservationDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFoodTruckName(): ?string
    {
        return $this->foodTruckName;
    }

    public function setFoodTruckName(string $foodTruckName): self
    {
        $this->foodTruckName = $foodTruckName;

        return $this;
    }

    public function getReservationDate(): ?\DateTimeInterface
    {
        return $this->reservationDate;
    }

    public function setReservationDate(\DateTimeInterface $reservationDate): self
    {
        $this->reservationDate = $reservationDate;

        return $this;
    }
}
