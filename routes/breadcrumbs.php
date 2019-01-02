<?php

// Home
Breadcrumbs::for('locations.index', function ($trail) {
    $trail->push(__('common.nav.home'), route('locations.index'));
});

// Home -> [Location]
Breadcrumbs::for('locations.show', function ($trail, \App\Models\Location $location) {
    $trail->parent('locations.index');
    $trail->push(__('location.show.title', ['postal_code' => $location->postal_code, 'name' => $location->name]));
});