<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * @param array $json
     * @return Location
     */
    public static function createFromJson($json)
    {
        $location = new Location();
        $location->uuid = $json['kenn'];
        $location->name = $json['ort'];
        $location->measurement_node_id = $json['kid'];
        $location->status_id = $json['status'];
        $location->height = $json['hoehe'];
        $location->longitude = $json['lon'];
        $location->last_measured_one_hour_value = array_key_exists('mw', $json) ? $json['mw'] : null;
        $location->latitude = $json['lat'];
        $location->postal_code = $json['plz'];

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
}
