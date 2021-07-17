<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $statistics = Statistic::orderBy('date', 'desc')->paginate();

        return view('statistic.index', [
            'statistics' => $statistics,
        ]);
    }
}
