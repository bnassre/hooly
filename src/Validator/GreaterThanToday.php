<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class GreaterThanToday extends Constraint
{
    public $message = 'La date de réservation "{{ string }}" doit être ultérieure à ce jour.';
}