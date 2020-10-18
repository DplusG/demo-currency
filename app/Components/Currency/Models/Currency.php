<?php

namespace App\Components\Currency\Models;


class Currency
{
    use GetSetTrait;

    protected $id;
    protected $code;
    protected $ticker;
    protected $name;
    protected $date;
    protected $rate;

    public function __construct()
    {
    }
}
