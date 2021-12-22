<?php

namespace App\Upgrades;

abstract class Upgrade
{
    abstract public function run(): int;
}
