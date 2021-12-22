<?php

namespace App\Models;

use App\Enums\LocationStatus;
use App\Libraries\Odl\Features\LocationFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DailyMeasurement[] $dailyMeasurements
 * @property-read int|null $daily_measurements_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\HourlyMeasurement[] $hourlyMeasurements
 * @property-read int|null $hourly_measurements_count
 * @property-read \App\Models\MeasurementNode $measurementNode
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLastMeasuredOneHourValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereMeasurementNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUuid($value)
 * @mixin \Eloquent
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereSlug($value)
 * @property string|null $uuid_new
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUuidNew($value)
 * @property string|null $status
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereStatus($value)
 */
class Location extends Model
{
    use HasSlug;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

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
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => LocationStatus::class,
    ];

    /**
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
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
        $location->status = Arr::get($json, 'status');
        $location->height = Arr::get($json, 'hoehe');
        $location->longitude = Arr::get($json, 'lon');
        $location->last_measured_one_hour_value = Arr::get($json, 'mw');
        $location->latitude = Arr::get($json, 'lat');
        $location->postal_code = Arr::get($json, 'plz');

        return $location;
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

    /**
     * @param LocationFeature $locationFeature
     * @return Location
     */
    public static function fromLocationFeature(LocationFeature $locationFeature)
    {
        $location = new self();

        $location->uuid = $locationFeature->properties->kenn;
        $location->uuid_new = $locationFeature->properties->id;
        $location->name = $locationFeature->properties->name;
        $location->postal_code = $locationFeature->properties->plz;
        $location->measurement_node_id = $locationFeature->properties->kid;
        $location->status = $locationFeature->properties->siteStatus;
        $location->height = $locationFeature->properties->heightAboveSea;
        $location->longitude = $locationFeature->geometry->coordinates->longitude;
        $location->latitude = $locationFeature->geometry->coordinates->latitude;
        $location->last_measured_one_hour_value = $locationFeature->properties->value;

        return $location;
    }
}
