<?php

use App\Models\Location;
use App\Models\UpdateLog;
use Tabuna\Breadcrumbs\Trail;

// Home
Breadcrumbs::for('locations.index', function (Trail $trail) {
    $trail->push(__('common.nav.home'), route('locations.index'));
});

// Home -> [Location]
Breadcrumbs::for('locations.show', function (Trail $trail, Location $location) {
    $trail->parent('locations.index');
    $trail->push(__('location.show.title', ['postal_code' => $location->postal_code, 'name' => $location->name]));
});

// Update logs
Breadcrumbs::for('update_logs.index', function (Trail $trail) {
    $trail->push(__('common.nav.update_logs'), route('update_logs.index'));
});

// Update logs -> [Update log]
Breadcrumbs::for('update_logs.show', function (Trail $trail, UpdateLog $updateLog) {
    $trail->parent('update_logs.index');
    $trail->push(__('update_log.show.title', ['date' => $updateLog->created_at->format(__('common.date_formats.datetime'))]));
});
