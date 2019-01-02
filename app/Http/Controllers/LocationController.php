<?php

namespace App\Http\Controllers;

use App\Charts\DailyMeasurementsChart;
use App\Charts\HourlyMeasurementsChart;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Mapper;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchTerm = $request->get('term');
        $locations = Location::orderBy('name');

        if ($searchTerm) {
            $locations = $locations
                ->where('name', 'like', "%$searchTerm%")
                ->orWhere('postal_code', 'like', "%$searchTerm%")
            ;
        }

        $locations = $locations->paginate();

        return view('location.index', compact('locations'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $dailyMinDate = Carbon::now()->subMonths(3);
        $hourlyMinDate = Carbon::now()->subWeek();
        $location = Location::find(getIdFromSlug($slug));

        $hourlyMeasurements = $location->hourlyMeasurements()->orderBy('date')->where('date', '>=', $hourlyMinDate);

        $hourlyMeasurementsChart = new HourlyMeasurementsChart();
        $hourlyMeasurementsChart->title(__('location.show.hourly_values'));
        $hourlyMeasurementsChart->labels($hourlyMeasurements->pluck('date')->map(function ($item) {
            return $item->format(l(DATETIME));
        }));
        $hourlyMeasurementsChart->dataset((__('location.show.measurement')), 'line', $hourlyMeasurements->pluck('value'));
        $hourlyMeasurementsChart->dataset(__('location.show.precipitation_probability'), 'line', $hourlyMeasurements->pluck('precipitation_probability'));

        $dailyMeasurements = $location->dailyMeasurements()->orderBy('date')->where('date', '>=', $dailyMinDate);

        $dailyMeasurementsChart = new DailyMeasurementsChart();
        $dailyMeasurementsChart->title(__('location.show.daily_values'));
        $dailyMeasurementsChart->labels($dailyMeasurements->pluck('date')->map(function ($item) {
            return $item->format(l(DATE));
        }));
        $dailyMeasurementsChart->dataset(__('location.show.measurement'), 'line', $dailyMeasurements->pluck('value')->map(function ($value) {
            if ($value == 0) {
                return null;
            }

            return $value;
        }));

        Mapper::map($location->latitude, $location->longitude);

        return view('location.show', compact('location', 'hourlyMeasurementsChart', 'dailyMeasurementsChart'));
    }
}
