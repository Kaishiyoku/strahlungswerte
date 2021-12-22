<?php

namespace App\Models;

use App\Libraries\Odl\Features\MeasurementFeature;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\HourlyMeasurement
 *
 * @property int $id
 * @property string $location_uuid
 * @property string|null $location_uuid_new
 * @property bool|null $is_validated
 * @property float|null $value
 * @property float|null $precipitation_probability
 * @property \Illuminate\Support\Carbon $date
 * @property-read \App\Models\Location $location
 * @method static \Illuminate\Database\Eloquent\Builder|HourlyMeasurement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HourlyMeasurement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HourlyMeasurement query()
 * @method static \Illuminate\Database\Eloquent\Builder|HourlyMeasurement whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HourlyMeasurement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HourlyMeasurement whereIsValidated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HourlyMeasurement whereLocationUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HourlyMeasurement whereLocationUuidNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HourlyMeasurement wherePrecipitationProbability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HourlyMeasurement whereValue($value)
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
        'date' => 'datetime',
        'is_validated' => 'bool',
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
     * @return HourlyMeasurement
     */
    public static function fromMeasurementFeature(MeasurementFeature $measurementFeature)
    {
        $hourlyMeasurement = new self();

        $hourlyMeasurement->location_uuid = $measurementFeature->properties->kenn;
        $hourlyMeasurement->location_uuid_new = $measurementFeature->properties->id;
        $hourlyMeasurement->is_validated = $measurementFeature->properties->validated;
        $hourlyMeasurement->value = $measurementFeature->properties->value;
        $hourlyMeasurement->precipitation_probability = null;
        $hourlyMeasurement->date = $measurementFeature->properties->endMeasure;

        return $hourlyMeasurement;
    }
}
