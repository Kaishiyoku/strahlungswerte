<?php

namespace App\Libraries\Odl\Models;

class PrecipitationProbability extends Measurement
{
    protected $date;
    protected $value;

    /**
     * @param $dateTime
     * @param $value
     */
    public function __construct($dateTime, $value)
    {
        $this->date = $dateTime;
        $this->value = $value;
    }


}
