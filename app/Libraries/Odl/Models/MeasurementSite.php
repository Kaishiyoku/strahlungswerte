<?php

namespace App\Libraries\Odl\Models;

use App\Models\Location;
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
    private $dailyMeasurements;

    /**
     * @var Collection
     */
    private $hourlyPrecipitationProbabilities;

    /**
     * @param array $json
     * @return MeasurementSite
     */
    public static function fromJson($json)
    {
        $measurementSite = new MeasurementSite();
        $measurementSite->location = Location::createFromJson($json['stamm']);
        $measurementSite->hourlyMeasurements = collect();
        $measurementSite->hourlyPrecipitationProbabilities = collect();
        $measurementSite->dailyMeasurements = collect();

        // hourly measurements
        $mw1hData = $json['mw1h'];
        $hourlyPrecipitationProbabilities = collect();

        foreach ($mw1hData['r'] as $key => $hourlyPrecipitationProbability) {
            $date = new Carbon($mw1hData['tr'][$key]);
            $value = $hourlyPrecipitationProbability ?: 0;

            $hourlyPrecipitationProbabilities->push(new PrecipitationProbability($date, $value));
        }

        foreach ($mw1hData['mw'] as $key => $hourlyMeasurement) {
            $value = $hourlyMeasurement ?: 0;
            $date = new Carbon($mw1hData['t'][$key]);
            $inspectionStatus = $mw1hData['ps'][$key];

            $precipitationProbability = $hourlyPrecipitationProbabilities->filter(function (PrecipitationProbability $item) use ($date) {
                return $item->getDate()->eq($date);
            })->first();

            $precipitationProbabilityValue = $precipitationProbability ? $precipitationProbability->getValue() : null;

            $measurementSite->hourlyMeasurements->push(new HourlyMeasurement($date, $value, $inspectionStatus, $precipitationProbabilityValue));
        }

        // daily measurements
        $mw24hData = $json['mw24h'];

        foreach ($mw24hData['mw'] as $key => $dailyMeasurement) {
            $value = $dailyMeasurement ?: 0;
            $date = new Carbon($mw24hData['t'][$key]);

            $measurementSite->dailyMeasurements->push(new DailyMeasurement($date, $value));
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
