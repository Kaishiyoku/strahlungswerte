<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\HourlyMeasurement
 *
 * @property int $id
 * @property string $location_uuid
 * @property int|null $inspection_status
 * @property float|null $value
 * @property float|null $precipitation_probability
 * @property \Illuminate\Support\Carbon $date
 * @property-read \App\Models\Location $location
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HourlyMeasurement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HourlyMeasurement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HourlyMeasurement query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HourlyMeasurement whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HourlyMeasurement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HourlyMeasurement whereInspectionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HourlyMeasurement whereLocationUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HourlyMeasurement wherePrecipitationProbability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HourlyMeasurement whereValue($value)
 * @mixin \Eloquent
 */
class HourlyMeasurement extends Model
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

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
