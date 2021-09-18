<?php

namespace App\Http\Controllers;

use App\Models\DailyMeasurement;
use App\Models\HourlyMeasurement;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
                ->orWhere('postal_code', 'like', "%$searchTerm%");
        }

        $locations = $locations->paginate();

        return view('location.index', [
            'locations' => $locations,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function find(Request $request)
    {
        $searchTerm = $request->get('term');

        if (!$searchTerm) {
            return response()->json([]);
        }

        $locationOptions = Location::orderBy('name')
            ->where('name', 'like', "%{$searchTerm}%")
            ->orWhere('postal_code', 'like', "%$searchTerm%")
            ->take(20)
            ->pluck('name')
            ->map(function ($name) {
                return [
                    'label' => $name,
                ];
            });

        return response()->json($locationOptions);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Location $location)
    {
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
        $hourlyMeasurementsForTable = $hourlyMeasurements->sortByDesc($hourlyMeasurementDateSortFn);

        $dailyMeasurements = $location->dailyMeasurements()->where('date', '>=', $dailyMinDate)->get();
        $dailyMeasurementsForChart = $dailyMeasurements->sortBy($dailyMeasurementDateSortFn);
        $dailyMeasurementsForTable = $dailyMeasurements->sortByDesc($dailyMeasurementDateSortFn);

        $hourlyMeasurementsChartData = [
            'labels' => $hourlyMeasurementsForChart->map(function (HourlyMeasurement $hourlyMeasurement) {
                return $hourlyMeasurement->date->format(__('common.date_formats.date'));
            }),
            'datasets' => [
                [
                    'name' => __('location.show.hourly_values_short'),
                    'chartType' => 'line',
                    'values' => $hourlyMeasurementsForChart->map(function (HourlyMeasurement $hourlyMeasurement) {
                        return $hourlyMeasurement->precipitation_probability;
                    }),
                ],
                [
                    'name' => __('location.show.precipitation_probability'),
                    'chartType' => 'line',
                    'values' => $hourlyMeasurementsForChart->map(function (HourlyMeasurement $hourlyMeasurement) {
                        return $hourlyMeasurement->value;
                    }),
                ],
            ],
            'yMarkers' => [
                [
                    'label' => '',
                    'value' => 0,
                    'type' => 'solid',
                ],
            ],
        ];

        $dailyMeasurementsChartData = [
            'labels' => $dailyMeasurementsForChart->map(function (DailyMeasurement $dailyMeasurement) {
                return $dailyMeasurement->date->format(__('common.date_formats.date'));
            }),
            'datasets' => [
                [
                    'name' => __('location.show.daily_values_short'),
                    'chartType' => 'line',
                    'values' => $dailyMeasurementsForChart->map(function (DailyMeasurement $dailyMeasurement) {
                        return $dailyMeasurement->value;
                    }),
                ],
            ],
            'yMarkers' => [
                [
                    'label' => '',
                    'value' => 0,
                    'type' => 'solid',
                ],
            ],
        ];

        return view('location.show', [
            'location' => $location,
            'hourlyMeasurements' => $hourlyMeasurements,
            'hourlyMeasurementsForTable' => $hourlyMeasurementsForTable,
            'dailyMeasurements' => $dailyMeasurements,
            'dailyMeasurementsForTable' => $dailyMeasurementsForTable,
            'hourlyMeasurementsChartData' => $hourlyMeasurementsChartData,
            'dailyMeasurementsChartData' => $dailyMeasurementsChartData,
        ]);
    }
}
