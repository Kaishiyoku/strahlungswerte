<?php

namespace App\Libraries\Odl\Features;

use Arr;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class FeatureCollection
{
    public string $type;

    public Collection $features;

    public int $totalFeatures;

    public int $numberMatched;

    public int $numberReturned;

    public Carbon $timeStamp;

    public Crs $crs;

    public static function fromJson(string $featureClassName, array $json): self
    {
        $featureCollection = new self();
        $featureCollection->features = collect(Arr::get($json, 'features'))->map(fn(array $featureJson) => $featureClassName::fromJson($featureJson));
        $featureCollection->totalFeatures = Arr::get($json, 'totalFeatures');
        $featureCollection->numberMatched = Arr::get($json, 'numberMatched');
        $featureCollection->numberReturned = Arr::get($json, 'numberReturned');
        $featureCollection->timeStamp = Carbon::parse(Arr::get($json, 'timeStamp'));
        $featureCollection->crs = Crs::fromJson(Arr::get($json, 'crs'));

        return $featureCollection;
    }
}
