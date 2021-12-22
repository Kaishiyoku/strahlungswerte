<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Operational()
 * @method static static Faulty()
 * @method static static TestMode()
 */
final class LocationStatus extends Enum
{
    const Operational = 1;
    const Faulty = 2;
    const TestMode = 3;
}
