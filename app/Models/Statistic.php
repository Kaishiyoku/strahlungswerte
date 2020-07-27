<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * App\Models\Statistic
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $date
 * @property int $number_of_operational_locations
 * @property float|null $average_value
 * @property string $min_location_uuid
 * @property float|null $min_value
 * @property string $max_location_uuid
 * @property float|null $max_value
 * @property-read \App\Models\Location $maxLocation
 * @property-read \App\Models\Location $minLocation
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Statistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Statistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Statistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Statistic whereAverageValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Statistic whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Statistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Statistic whereMaxLocationUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Statistic whereMaxValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Statistic whereMinLocationUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Statistic whereMinValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Statistic whereNumberOfOperationalLocations($value)
 * @mixin \Eloquent
 */
class Statistic extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];

    public function minLocation()
    {
        return $this->belongsTo(Location::class, 'min_location_uuid');
    }

    public function maxLocation()
    {
        return $this->belongsTo(Location::class, 'max_location_uuid');
    }

    /**
     * @param array $json
     * @return Statistic
     */
    public static function createFromJson($json)
    {
        $statistic = new Statistic();
        $statistic->date = Carbon::parse(Arr::get($json, 'mwavg.t'));
        $statistic->number_of_operational_locations = Arr::get($json, 'betriebsbereit');
        $statistic->average_value = Arr::get($json, 'mwavg.mw');
        $statistic->min_location_uuid = Arr::get($json, 'mwmin.kenn');
        $statistic->min_value = Arr::get($json, 'mwmin.mw');
        $statistic->max_location_uuid = Arr::get($json, 'mwmax.kenn');
        $statistic->max_value = Arr::get($json, 'mwmax.mw');

        return $statistic;
    }
}