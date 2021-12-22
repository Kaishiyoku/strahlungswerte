<?php

namespace App\Libraries\Odl\Features;

use Arr;

class Crs
{
    public string $type;

    public CrsProperties $properties;

    public static function fromJson(array $json): self
    {
        $crs = new self();
        $crs->type = Arr::get($json, 'type');
        $crs->properties = CrsProperties::fromJson(Arr::get($json, 'properties'));

        return $crs;
    }
}
