<?php

namespace App\Http\Controllers;

use App\Models\UpdateLog;
use Illuminate\Http\Request;

class UpdateLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $updateLogs = UpdateLog::paginate();

        return view('update_log.index', compact('updateLogs'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UpdateLog  $updateLog
     * @return \Illuminate\Http\Response
     */
    public function show(UpdateLog $updateLog)
    {
        return view('update_log.show', compact('updateLog'));
    }
}
