<?php

namespace App\Http\Controllers;

use App\Charts\DailyMeasurementsChart;
use App\Charts\HourlyMeasurementsChart;
use App\Models\Location;
use Illuminate\Support\Carbon;
use Mapper;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::orderBy('name')->paginate();

        return view('location.index', compact('locations'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $location
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $dailyMinDate = Carbon::now()->subMonths(6);
        $hourlyMinDate = Carbon::now()->subWeek();
        $location = Location::find(getIdFromSlug($slug));

        $hourlyMeasurements = $location->hourlyMeasurements()->orderBy('date')->where('date', '>=', $hourlyMinDate);

        $hourlyMeasurementsChart = new HourlyMeasurementsChart();
        $hourlyMeasurementsChart->title('Stündliche Werte');
        $hourlyMeasurementsChart->labels($hourlyMeasurements->pluck('date')->map(function ($item) {
            return $item->format(l(DATETIME));
        }));
        $hourlyMeasurementsChart->dataset('Messung (µSv/h)', 'areaspline', $hourlyMeasurements->pluck('value'));
        $hourlyMeasurementsChart->dataset('Niederschlagswahrscheinlichkeit', 'areaspline', $hourlyMeasurements->pluck('precipitation_probability'));

        $dailyMeasurements = $location->dailyMeasurements()->orderBy('date')->where('date', '>=', $dailyMinDate);

        $dailyMeasurementsChart = new DailyMeasurementsChart();
        $dailyMeasurementsChart->title('Tägliche Werte');
        $dailyMeasurementsChart->labels($dailyMeasurements->pluck('date')->map(function ($item) {
            return $item->format(l(DATE));
        }));
        $dailyMeasurementsChart->dataset('Messung (µSv/h)', 'area', $dailyMeasurements->pluck('value')->map(function ($value) {
            if ($value == 0) {
                return null;
            }

            return $value;
        }));

        Mapper::map($location->latitude, $location->longitude);

        return view('location.show', compact('location', 'hourlyMeasurementsChart', 'dailyMeasurementsChart'));
    }
}
