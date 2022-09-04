<?php

namespace App\Dto;

class CreateFoodTrucksReservationDto
{
    private string $foodTruckName;
    private \DateTime $reservationDate;

    static function of(string $foodTruckName,  \DateTime $reservationDate): CreateFoodTrucksReservationDto
    {
        $dto = new CreateFoodTrucksReservationDto();
        $dto->setFoodTruckName($foodTruckName)->setReservationDate($reservationDate);
        return $dto;
    }

    /**
     * @param string $foodTruckName
     */
    public function setFoodTruckName(string $foodTruckName): self
    {
        $this->foodTruckName = $foodTruckName;
        return $this;
    }

    /**
     * @param \DateTime $reservationDate
     */
    public function setReservationDate(\DateTime $reservationDate): self
    {
        $this->reservationDate = $reservationDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getFoodTruckName(): string
    {
        return $this->foodTruckName;
    }

    /**
     * @return \DateTime
     */
    public function getReservationDate(): \DateTime
    {
        return $this->reservationDate;
    }
}