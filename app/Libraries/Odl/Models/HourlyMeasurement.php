<?php

namespace App\Libraries\Odl\Models;

class HourlyMeasurement extends Measurement
{
    private $inspectionStatus;

    /**
     * @param $date
     * @param $value
     * @param $inspectionStatus
     */
    public function __construct($date, $value, $inspectionStatus)
    {
        $this->date = $date;
        $this->value = $value;
        $this->inspectionStatus = $inspectionStatus;
    }
}
