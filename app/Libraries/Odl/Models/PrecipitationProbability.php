<?php

namespace App\Libraries\Odl\Models;

class PrecipitationProbability extends Measurement
{
    /**
     * @param $date
     * @param $value
     */
    public function __construct($date, $value)
    {
        $this->date = $date;
        $this->value = $value;
    }


}
