<?php

namespace App\Models;

use App\Libraries\Odl\Features\MeasurementFeature;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DailyMeasurement
 *
 * @property int $id
 * @property string $location_uuid
 * @property float|null $value
 * @property \Illuminate\Support\Carbon $date
 * @property-read \App\Models\Location $location
 * @method static \Illuminate\Database\Eloquent\Builder|DailyMeasurement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyMeasurement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyMeasurement query()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyMeasurement whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyMeasurement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyMeasurement whereLocationUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyMeasurement whereValue($value)
 * @mixin \Eloquent
 * @property string|null $location_uuid_new
 * @method static \Illuminate\Database\Eloquent\Builder|DailyMeasurement whereLocationUuidNew($value)
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

    /**
     * @param MeasurementFeature $measurementFeature
     * @return DailyMeasurement
     */
    public static function fromMeasurementFeature(MeasurementFeature $measurementFeature)
    {
        $dailyMeasurement = new self();

        $dailyMeasurement->location_uuid = $measurementFeature->properties->kenn;
        $dailyMeasurement->location_uuid_new = $measurementFeature->properties->id;
        $dailyMeasurement->value = $measurementFeature->properties->value;
        $dailyMeasurement->date = $measurementFeature->properties->startMeasure;

        return $dailyMeasurement;
    }
}
