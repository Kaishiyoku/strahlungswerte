<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MeasurementNode
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $locations
 * @property-read int|null $locations_count
 * @method static \Illuminate\Database\Eloquent\Builder|MeasurementNode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeasurementNode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeasurementNode query()
 * @method static \Illuminate\Database\Eloquent\Builder|MeasurementNode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeasurementNode whereName($value)
 * @mixin \Eloquent
 */
class MeasurementNode extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
