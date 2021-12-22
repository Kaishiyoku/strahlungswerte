<?php

namespace App\Libraries\Odl\Features;

use Arr;

class MeasurementFeature extends BaseFeature
{
    public string $type;

    public string $id;

    public Geometry $geometry;

    public string $geometryName;

    public MeasurementProperties $properties;

    public static function fromJson(array $json): self
    {
        $measurementFeature = new self();
        $measurementFeature->type = Arr::get($json, 'type');
        $measurementFeature->id = Arr::get($json, 'id');
        $measurementFeature->geometry = Geometry::fromJson(Arr::get($json, 'geometry'));
        $measurementFeature->geometryName = Arr::get($json, 'geometry_name');
        $measurementFeature->properties = MeasurementProperties::fromJson(Arr::get($json, 'properties'));

        return $measurementFeature;
    }
}
