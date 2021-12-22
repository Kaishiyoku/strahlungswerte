<?php

namespace App\Libraries\Odl\Features;

use Arr;

class Coordinates
{
    public string $longitude;

    public string $latitude;

    public static function fromJson(array $json): self
    {
        $coordinates = new self();
        $coordinates->longitude = Arr::get($json, 0);
        $coordinates->latitude = Arr::get($json, 1);

        return $coordinates;
    }
}
