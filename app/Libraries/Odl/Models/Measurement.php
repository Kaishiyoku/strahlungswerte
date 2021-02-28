<?php

namespace App\Libraries\Odl\Models;

use Carbon\Carbon;

abstract class Measurement
{
    /**
     * @var Carbon
     */
    protected $date;

    /**
     * @var float|null
     */
    protected $value;

    /**
     * @param Carbon $date
     * @param float|null $value
     */
    public function __construct(Carbon $date, ?float $value)
    {
        $this->date = $date;
        $this->value = $value;
    }

    /**
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }
}
