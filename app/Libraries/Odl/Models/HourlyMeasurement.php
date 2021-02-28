<?php

namespace App\Libraries\Odl\Models;

use Carbon\Carbon;

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
     * @param Carbon $date
     * @param float|null $value
     * @param int|null $inspectionStatus
     * @param int|null $precipitationProbabilityValue
     */
    public function __construct(Carbon $date, ?float $value, ?int $inspectionStatus, ?int $precipitationProbabilityValue)
    {
        parent::__construct($date, $value);

        $this->inspectionStatus = $inspectionStatus;
        $this->precipitationProbabilityValue = $precipitationProbabilityValue;
    }

    /**
     * @return int|null
     */
    public function getInspectionStatus(): ?int
    {
        return $this->inspectionStatus;
    }

    /**
     * @param int|null $inspectionStatus
     */
    public function setInspectionStatus(?int $inspectionStatus): void
    {
        $this->inspectionStatus = $inspectionStatus;
    }

    /**
     * @return float|null
     */
    public function getPrecipitationProbabilityValue(): ?float
    {
        return $this->precipitationProbabilityValue;
    }

    /**
     * @param float|null $precipitationProbability
     */
    public function setPrecipitationProbabilityValue(?float $precipitationProbabilityValue): void
    {
        $this->precipitationProbabilityValue = $precipitationProbabilityValue;
    }
}
