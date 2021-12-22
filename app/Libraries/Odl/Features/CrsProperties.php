<?php

namespace App\Libraries\Odl\Features;

use Arr;

class CrsProperties
{
    public string $name;

    public static function fromJson(array $json): self
    {
        $crsProperties = new self();
        $crsProperties->name = Arr::get($json, 'name');

        return $crsProperties;
    }
}
