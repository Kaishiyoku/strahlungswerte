<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DailyMeasurement
 *
 * @property int $id
 * @property string $location_uuid
 * @property float|null $value
 * @property \Illuminate\Support\Carbon $date
 * @property-read \App\Models\Location $location
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DailyMeasurement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DailyMeasurement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DailyMeasurement query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DailyMeasurement whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DailyMeasurement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DailyMeasurement whereLocationUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DailyMeasurement whereValue($value)
 * @mixin \Eloquent
 */
class DailyMeasurement extends Model
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
