<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use ConsoleTVs\Charts\Classes\DatasetClass;
use Illuminate\Support\Facades\View;

abstract class BaseChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set the chart container.
     *
     * @param string $container
     *
     * @return self
     */
    public function container(string $container = null)
    {
        if (!$container) {
            $this->datasets = array_map(function ($key, DatasetClass $dataset) {
                return $dataset->options([
                    'borderColor' => config('charts.colors')[$key],
                    'backgroundColor' => config('charts.colors')[$key],
                ]);
            }, array_keys($this->datasets), $this->datasets);

            return View::make($this->container, ['chart' => $this]);
        }

        $this->container = $container;

        return $this;
    }
}
