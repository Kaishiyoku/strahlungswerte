<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UpdateLog
 *
 * @property int $id
 * @property bool $is_successful
 * @property string $command_name
 * @property int $number_of_new_entries
 * @property int $duration_in_seconds
 * @property string|null $json_content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateLog whereCommandName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateLog whereDurationInSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateLog whereIsSuccessful($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateLog whereJsonContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateLog whereNumberOfNewEntries($value)
 * @mixin \Eloquent
 */
class UpdateLog extends Model
{
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
        'is_successful' => 'boolean',
    ];

    public function setUpdatedAt($value)
    {
        //
    }
}
