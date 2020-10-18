<?php

namespace App\Components\Validators;

use DateTime;

class DateValidator
{
    public static function validateFormat($date, $format = 'd/m/Y')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}