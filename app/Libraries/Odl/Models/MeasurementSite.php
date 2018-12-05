<?php

namespace App\Libraries\Odl\Models;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class MeasurementSite
{
    /**
     * @var Location $location
     */
    private $location;

    /**
     * @var Collection
     */
    private $hourlyMeasurements;

    /**
     * @var Collection
     */
    private $hourlyPrecipitationProbabilities;

    /**
     * @var Collection
     */
    private $dailyMeasurements;

    /**
     * @param array $json
     * @return MeasurementSite
     */
    public static function fromJson($json)
    {
        $measurementSite = new MeasurementSite();
        $measurementSite->location = Location::fromJson($json['stamm']);
        $measurementSite->hourlyMeasurements = collect();
        $measurementSite->hourlyPrecipitationProbabilities = collect();
        $measurementSite->dailyMeasurements = collect();

        // hourly measurements
        $mw1hData = $json['mw1h'];

        foreach ($mw1hData['mw'] as $key => $hourlyMeasurement) {
            $value = $hourlyMeasurement ?: 0;
            $dateTime = new Carbon($mw1hData['t'][$key]);
            $inspectionStatus = $mw1hData['ps'][$key];

            $measurementSite->hourlyMeasurements->push(new HourlyMeasurement($dateTime, $value, $inspectionStatus));
        }

        // hourly precipitation probabilities
        foreach ($mw1hData['r'] as $key => $hourlyPrecipitationProbability) {
            $value = $hourlyPrecipitationProbability ?: 0;
            $dateTime = new Carbon($mw1hData['tr'][$key]);

            $measurementSite->hourlyPrecipitationProbabilities->push(new PrecipitationProbability($dateTime, $value));
        }

        // daily measurements
        $mw24hData = $json['mw24h'];

        foreach ($mw24hData['mw'] as $key => $dailyMeasurement) {
            $value = $dailyMeasurement ?: 0;
            $dateTime = new Carbon($mw24hData['t'][$key]);

            $measurementSite->dailyMeasurements->push(new DailyMeasurement($dateTime, $value));
        }

        // reverse collections
        $measurementSite->hourlyMeasurements = $measurementSite->hourlyMeasurements->reverse();
        $measurementSite->dailyMeasurements = $measurementSite->dailyMeasurements->reverse();
        $measurementSite->hourlyPrecipitationProbabilities = $measurementSite->hourlyPrecipitationProbabilities->reverse();

        return $measurementSite;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return Collection
     */
    public function getHourlyMeasurements()
    {
        return $this->hourlyMeasurements;
    }

    /**
     * @return Collection
     */
    public function getHourlyPrecipitationProbabilities()
    {
        return $this->hourlyPrecipitationProbabilities;
    }

    /**
     * @return Collection
     */
    public function getDailyMeasurements()
    {
        return $this->dailyMeasurements;
    }
}
