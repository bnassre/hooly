<?php
declare(strict_types=1);

namespace App\Requests;

use App\Validator as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReservationFoodTruckRequest extends BaseRequest
{


    #[Assert\Type('string')]
    #[Assert\NotBlank()]
    protected $foodTruckName;


    #[Assert\NotBlank([])]
    #[Assert\Date()]
    #[AppAssert\GreaterThanToday()]
    protected $reservationDate;
}