<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

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
        $uuid = getIdFromSlug($slug);

        $location = Location::find($uuid);

        return view('location.show', compact('location'));
    }
}
