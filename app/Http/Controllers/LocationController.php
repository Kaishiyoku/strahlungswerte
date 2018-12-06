<?php

namespace App\Http\Controllers;

use App\Charts\DailyMeasurementsChart;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        $minDate = Carbon::now()->subMonth();
        $location = Location::find(getIdFromSlug($slug));
        $dailyMeasurements = $location->dailyMeasurements()->orderBy('date')->where('date', '>=', $minDate);

        $dailyMeasurementsChart = new DailyMeasurementsChart();
        $dailyMeasurementsChart->labels($dailyMeasurements->pluck('date')->map(function ($item) {
            return $item->format(l(DATE));
        }));
        $dailyMeasurementsChart->dataset('Messung (µSv/h)', 'line', $dailyMeasurements->pluck('value'));

        return view('location.show', compact('location', 'dailyMeasurementsChart'));
    }
}
