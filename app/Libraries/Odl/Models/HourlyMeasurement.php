<?php

namespace App\Libraries\Odl\Models;

class HourlyMeasurement extends Measurement
{
    /**
     * @var int
     */
    private $inspectionStatus;

    /**
     * @var float
     */
    private $precipitationProbabilityValue;

    /**
     * @param $date
     * @param $value
     * @param $inspectionStatus
     * @param $precipitationProbabilityValue
     */
    public function __construct($date, $value, $inspectionStatus, $precipitationProbabilityValue)
    {
        $this->date = $date;
        $this->value = $value;
        $this->inspectionStatus = $inspectionStatus;
        $this->precipitationProbabilityValue = $precipitationProbabilityValue;
    }

    /**
     * @return mixed
     */
    public function getInspectionStatus()
    {
        return $this->inspectionStatus;
    }

    /**
     * @param mixed $inspectionStatus
     */
    public function setInspectionStatus($inspectionStatus): void
    {
        $this->inspectionStatus = $inspectionStatus;
    }

    /**
     * @return float|null
     */
    public function getPrecipitationProbabilityValue()
    {
        return $this->precipitationProbabilityValue;
    }

    /**
     * @param float $precipitationProbability
     */
    public function setPrecipitationProbabilityValue(float $precipitationProbabilityValue): void
    {
        $this->precipitationProbabilityValue = $precipitationProbabilityValue;
    }
}
