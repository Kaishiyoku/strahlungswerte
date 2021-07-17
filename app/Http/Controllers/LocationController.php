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
     * @return \Illuminate\Contracts\View\View
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

        return view('location.index', [
            'locations' => $locations,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Location $location)
    {
        $laravelRecharts = new LaravelRecharts();

        $hourlyMinDate = Carbon::now()->subDays(3);
        $dailyMinDate = Carbon::now()->subMonths(2);

        $hourlyMeasurementDateSortFn = function (HourlyMeasurement $hourlyMeasurement) {
            return $hourlyMeasurement->date->timestamp;
        };

        $dailyMeasurementDateSortFn = function (DailyMeasurement $dailyMeasurement) {
            return $dailyMeasurement->date->timestamp;
        };

        $hourlyMeasurements = $location->hourlyMeasurements()->where('date', '>=', $hourlyMinDate)->get();
        $hourlyMeasurementsForChart = $hourlyMeasurements->sortBy($hourlyMeasurementDateSortFn);

        $hourlyMeasurementsChart = $laravelRecharts->makeChart([
                LaravelRecharts::element(__('location.show.hourly_values'), LaravelRecharts::TYPE_AREA, 'rgba(0, 123, 255, .75)'),
                LaravelRecharts::element(__('location.show.precipitation_probability'), LaravelRecharts::TYPE_AREA, 'rgba(232, 62, 140, .75)'),
            ],
            $hourlyMeasurementsForChart->map(function (HourlyMeasurement $hourlyMeasurement) {
                return [
                    'name' => $hourlyMeasurement->date->format(__('common.date_formats.datetime')),
                    __('location.show.hourly_values') => $hourlyMeasurement->value,
                    __('location.show.precipitation_probability') => $hourlyMeasurement->precipitation_probability,
                ];
            })->toArray(),
            300
        );

        $hourlyMeasurementsForTable = $hourlyMeasurements->sortByDesc($hourlyMeasurementDateSortFn);

        $dailyMeasurements = $location->dailyMeasurements()->where('date', '>=', $dailyMinDate)->get();
        $dailyMeasurementsForChart = $dailyMeasurements->sortBy($dailyMeasurementDateSortFn);

        $dailyMeasurementsChart = $laravelRecharts->makeChart(
            [LaravelRecharts::element(__('location.show.daily_values'), LaravelRecharts::TYPE_AREA, 'rgba(102, 16, 242, .75)')],
            $dailyMeasurementsForChart->map(function (DailyMeasurement $dailyMeasurement) {
                return [
                    'name' => $dailyMeasurement->date->format(__('common.date_formats.date')),
                    __('location.show.daily_values') => $dailyMeasurement->value,
                ];
            })->toArray(),
            300
        );

        $dailyMeasurementsForTable = $dailyMeasurements->sortByDesc($dailyMeasurementDateSortFn);

        return view('location.show', [
            'location' => $location,
            'hourlyMeasurementsChart' => $hourlyMeasurementsChart,
            'dailyMeasurementsChart' => $dailyMeasurementsChart,
            'hourlyMeasurements' => $hourlyMeasurements,
            'hourlyMeasurementsForTable' => $hourlyMeasurementsForTable,
            'dailyMeasurements' => $dailyMeasurements,
            'dailyMeasurementsForTable' => $dailyMeasurementsForTable,
        ]);
    }
}
