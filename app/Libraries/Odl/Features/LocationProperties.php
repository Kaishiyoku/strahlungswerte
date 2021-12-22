<?php

namespace App\Libraries\Odl\Features;

use App\Enums\LocationStatus;
use Arr;
use Carbon\Carbon;

class LocationProperties
{
    public string $id;

    public string $kenn;

    public string $plz;

    public string $name;

    public LocationStatus $siteStatus;

    public string $siteStatusText;

    public int $kid;

    public int $heightAboveSea;

    public ?Carbon $startMeasure;

    public ?Carbon $endMeasure;

    public ?float $value;

    public ?float $valueCosmic;

    public ?float $valueTerrestrial;

    public string $unit;

    public ?int $validated;

    public string $nuclide;

    public string $duration;

    public static function fromJson(array $json): self
    {
        $startMeasureValue = Arr::get($json, 'start_measure');
        $endMeasureValue = Arr::get($json, 'end_measure');

        $locationProperties = new self();
        $locationProperties->id = Arr::get($json, 'id');
        $locationProperties->kenn = Arr::get($json, 'kenn');
        $locationProperties->plz = Arr::get($json, 'plz');
        $locationProperties->name = Arr::get($json, 'name');
        $locationProperties->siteStatus = LocationStatus::fromValue(Arr::get($json, 'site_status'));
        $locationProperties->siteStatusText = Arr::get($json, 'site_status_text');
        $locationProperties->kid = Arr::get($json, 'kid');
        $locationProperties->heightAboveSea = Arr::get($json, 'height_above_sea');
        $locationProperties->startMeasure = $startMeasureValue ? Carbon::parse($startMeasureValue) : null;
        $locationProperties->endMeasure = $endMeasureValue ? Carbon::parse($endMeasureValue) : null;
        $locationProperties->value = Arr::get($json, 'value');
        $locationProperties->valueCosmic = Arr::get($json, 'value_cosmic');
        $locationProperties->valueTerrestrial = Arr::get($json, 'value_terrestrial');
        $locationProperties->unit = Arr::get($json, 'unit');
        $locationProperties->validated = Arr::get($json, 'validated');
        $locationProperties->nuclide = Arr::get($json, 'nuclide');
        $locationProperties->duration = Arr::get($json, 'duration');

        return $locationProperties;
    }
}
