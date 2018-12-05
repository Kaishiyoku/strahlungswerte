<?php

namespace App\Libraries\Odl\Models;

class DailyMeasurement extends Measurement
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
