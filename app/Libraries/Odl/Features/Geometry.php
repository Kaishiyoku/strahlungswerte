<?php

namespace App\Libraries\Odl\Features;

use Arr;
use Illuminate\Support\Collection;

class Geometry
{
    public string $type;

    public Coordinates $coordinates;

    public static function fromJson(array $json): self
    {
        $geometry = new self();
        $geometry->type = Arr::get($json, 'type');
        $geometry->coordinates = Coordinates::fromJson(Arr::get($json, 'coordinates'));

        return $geometry;
    }
}
