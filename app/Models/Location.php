<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * App\Models\Location
 *
 * @property string $uuid
 * @property string $name
 * @property string $postal_code
 * @property int $measurement_node_id
 * @property int $status_id
 * @property int $height
 * @property float $longitude
 * @property float $latitude
 * @property float|null $last_measured_one_hour_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MeasurementNode $measurementNode
 * @property-read \App\Models\Status $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereLastMeasuredOneHourValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereMeasurementNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereUuid($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DailyMeasurement[] $dailyMeasurements
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\HourlyMeasurement[] $hourlyMeasurements
 * @property-read int|null $daily_measurements_count
 * @property-read int|null $hourly_measurements_count
 */
class Location extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

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

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return toSlug($this->uuid, $this->name);
    }

    /**
     * @param array $json
     * @return Location
     */
    public static function createFromJson($json)
    {
        $location = new Location();
        $location->uuid = Arr::get($json, 'kenn');
        $location->name = Arr::get($json, 'ort');
        $location->measurement_node_id = Arr::get($json, 'kid');
        $location->status_id = Arr::get($json, 'status');
        $location->height = Arr::get($json, 'hoehe');
        $location->longitude = Arr::get($json, 'lon');
        $location->last_measured_one_hour_value = Arr::get($json, 'mw');
        $location->latitude = Arr::get($json, 'lat');
        $location->postal_code = Arr::get($json, 'plz');

        return $location;
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function measurementNode()
    {
        return $this->belongsTo(MeasurementNode::class);
    }

    public function dailyMeasurements()
    {
        return $this->hasMany(DailyMeasurement::class);
    }

    public function hourlyMeasurements()
    {
        return $this->hasMany(HourlyMeasurement::class);
    }
}
