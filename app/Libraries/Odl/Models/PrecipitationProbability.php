<?php

namespace App\Libraries\Odl\Models;

class PrecipitationProbability extends Measurement
{
    protected $dateTime;
    protected $value;

    /**
     * @param $dateTime
     * @param $value
     */
    public function __construct($dateTime, $value)
    {
        $this->dateTime = $dateTime;
        $this->value = $value;
    }


}
