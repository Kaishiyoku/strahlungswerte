<?php

namespace App\Libraries\Odl\Models;

class DailyMeasurement extends Measurement
{
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
