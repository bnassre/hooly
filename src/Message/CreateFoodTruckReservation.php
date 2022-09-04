<?php

namespace App\Message;

use App\Dto\CreateFoodTrucksReservationDto;

class CreateFoodTruckReservation
{

      /**
     * @param CreateFoodTrucksReservationDto $foodTruckDto
     */
    public function __construct(private CreateFoodTrucksReservationDto $foodTruckDto)
    {
    }

    public function getFoodTruckDto(): CreateFoodTrucksReservationDto 
    {
        return $this->foodTruckDto;
    }
}