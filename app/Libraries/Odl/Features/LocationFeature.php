<?php

namespace App\Libraries\Odl\Features;

use Arr;

class LocationFeature extends BaseFeature
{
    public string $type;

    public string $id;

    public Geometry $geometry;

    public string $geometryName;

    public LocationProperties $properties;

    public static function fromJson(array $json): self
    {
        $locationFeature = new self();
        $locationFeature->type = Arr::get($json, 'type');
        $locationFeature->id = Arr::get($json, 'id');
        $locationFeature->geometry = Geometry::fromJson(Arr::get($json, 'geometry'));
        $locationFeature->geometryName = Arr::get($json, 'geometry_name');
        $locationFeature->properties = LocationProperties::fromJson(Arr::get($json, 'properties'));

        return $locationFeature;
    }
}
