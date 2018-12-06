<?php

namespace App\Http\Controllers;

use App\Models\Location;

class HomeController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('name')->paginate();

        return view('home.index', compact('locations'));
    }

    public function show($slug)
    {
        $uuid = getIdFromSlug($slug);

//        $odl = new OdlFetcher(env('ODL_BASE_URL'), env('ODL_USER'), env('ODL_PASSWORD'));
//        $measurementSite = $odl->getMeasurementSite($uuid);

        return view('home.show');
    }
}