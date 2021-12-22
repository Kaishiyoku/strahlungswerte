<?php

namespace App\Libraries\Odl\Features;

use App\Enums\LocationStatus;
use Arr;
use Carbon\Carbon;

class MeasurementProperties
{
    public string $id;

    public string $kenn;

    public string $name;

    public ?Carbon $startMeasure;

    public ?Carbon $endMeasure;

    public ?float $value;

    public string $unit;

    public bool $isValidated;

    public string $nuclide;

    public string $duration;

    public static function fromJson(array $json): self
    {
        $startMeasureValue = Arr::get($json, 'start_measure');
        $endMeasureValue = Arr::get($json, 'end_measure');

        $locationProperties = new self();
        $locationProperties->id = Arr::get($json, 'id');
        $locationProperties->kenn = Arr::get($json, 'kenn');
        $locationProperties->name = Arr::get($json, 'name');
        $locationProperties->startMeasure = $startMeasureValue ? Carbon::parse($startMeasureValue) : null;
        $locationProperties->endMeasure = $endMeasureValue ? Carbon::parse($endMeasureValue) : null;
        $locationProperties->value = Arr::get($json, 'value');
        $locationProperties->unit = Arr::get($json, 'unit');
        $locationProperties->isValidated = Arr::get($json, 'validated');
        $locationProperties->nuclide = Arr::get($json, 'nuclide');
        $locationProperties->duration = Arr::get($json, 'duration');

        return $locationProperties;
    }
}
