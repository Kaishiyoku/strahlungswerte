<?php

namespace App\Libraries\Odl\Models;

class HourlyMeasurement extends Measurement
{
    private $inspectionStatus;

    /**
     * @param $dateTime
     * @param $value
     * @param $inspectionStatus
     */
    public function __construct($dateTime, $value, $inspectionStatus)
    {
        $this->dateTime = $dateTime;
        $this->value = $value;
        $this->inspectionStatus = $inspectionStatus;
    }
}
