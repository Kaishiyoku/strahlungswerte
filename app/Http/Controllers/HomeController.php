<?php

namespace App\Http\Controllers;

use App\Libraries\Odl\OdlFetcher;

class HomeController extends Controller
{
    public function index()
    {
        $odlFetcher = new OdlFetcher(env('ODL_BASE_URL'), env('ODL_USER'), env('ODL_PASSWORD'));
        $locations = $odlFetcher->fetchLocations()->sortBy('postalCode');

        return view('home.index', compact('locations'));
    }

    public function show($slug)
    {
        $uuid = getIdFromSlug($slug);

        $odl = new OdlFetcher(env('ODL_BASE_URL'), env('ODL_USER'), env('ODL_PASSWORD'));
        $measurementSite = $odl->getMeasurementSite($uuid);

        dd($measurementSite);

        return view('home.show', compact('measurementSite'));
    }
}