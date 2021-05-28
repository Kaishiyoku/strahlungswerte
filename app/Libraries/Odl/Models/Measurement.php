<?php

namespace App\Libraries\Odl\Models;

abstract class Measurement
{
    protected $date;
    protected $value;

    public function getDate()
    {
        return $this->date;
    }

    public function getValue()
    {
        return $this->value;
    }
}
