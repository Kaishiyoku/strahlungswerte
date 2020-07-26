<?php

namespace App\Http\Controllers;

use App\Models\DailyMeasurement;
use App\Models\HourlyMeasurement;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Kaishiyoku\LaravelRecharts\LaravelRecharts;

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
        $laravelRecharts = new LaravelRecharts();

        $hourlyMinDate = Carbon::now()->subDays(3);
        $dailyMinDate = Carbon::now()->subMonths(2);
        $location = Location::find(getIdFromSlug($slug));

        $hourlyMeasurements = $location->hourlyMeasurements()->where('date', '>=', $hourlyMinDate);
        $hourlyMeasurementsForChart = (clone $hourlyMeasurements)->orderBy('date');

        $hourlyMeasurementsChart = $laravelRecharts->makeChart(
            [LaravelRecharts::element(__('location.show.hourly_values'), LaravelRecharts::TYPE_AREA, 'rgba(0, 123, 255, .75)')],
            $hourlyMeasurementsForChart->get()->map(function (HourlyMeasurement $hourlyMeasurement) {
                return [
                    'name' => $hourlyMeasurement->date->format(__('common.date_formats.date_time')),
                    __('location.show.hourly_values') => $hourlyMeasurement->value,
                ];
            })->toArray(),
            300
        );

        $dailyMeasurements = $location->dailyMeasurements()->where('date', '>=', $dailyMinDate);
        $dailyMeasurementsForChart = (clone $dailyMeasurements)->orderBy('date');

        $dailyMeasurementsChart = $laravelRecharts->makeChart(
            [LaravelRecharts::element(__('location.show.daily_values'), LaravelRecharts::TYPE_AREA, 'rgba(102, 16, 242, .75)')],
            $dailyMeasurementsForChart->get()->map(function (DailyMeasurement $dailyMeasurement) {
                return [
                    'name' => $dailyMeasurement->date->format(__('common.date_formats.date')),
                    __('location.show.daily_values') => $dailyMeasurement->value,
                ];
            })->toArray(),
            300
        );

        return view('location.show', compact('location', 'hourlyMeasurementsChart', 'dailyMeasurementsChart', 'hourlyMeasurements', 'dailyMeasurements'));
    }
}
