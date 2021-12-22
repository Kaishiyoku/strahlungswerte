<?php

namespace App\Libraries\Odl\Features;

abstract class BaseFeature
{
    abstract public static function fromJson(array $json): self;
}
