<?php

namespace App\Libraries\Odl\Models;

abstract class Measurement
{
    protected $dateTime;
    protected $value;

    public function getDateTime()
    {
        return $this->dateTime;
    }

    public function getValue()
    {
        return $this->value;
    }
}
