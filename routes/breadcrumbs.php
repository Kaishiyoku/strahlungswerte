<?php

use App\Models\Location;
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
