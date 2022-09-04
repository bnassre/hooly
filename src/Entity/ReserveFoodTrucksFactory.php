<?php

namespace App\Entity;

use App\Repository\ReserveFoodTrucksFactoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


class ReserveFoodTrucksFactory
{

    public static function create(string $foodTruckName, $reservationDate): ReserveFoodTrucks
    {
        $reserveFoodTrucks = new ReserveFoodTrucks();
        $reserveFoodTrucks->setFoodTruckName($foodTruckName);
        $reserveFoodTrucks->setReservationDate($reservationDate);
        return $reserveFoodTrucks;
    }
}
