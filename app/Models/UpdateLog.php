<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UpdateLog
 *
 * @property int $id
 * @property int $is_successful
 * @property string $command_name
 * @property int $number_of_new_entries
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UpdateLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UpdateLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UpdateLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UpdateLog whereCommandName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UpdateLog whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UpdateLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UpdateLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UpdateLog whereIsSuccessful($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UpdateLog whereNumberOfNewEntries($value)
 * @mixin \Eloquent
 * @property string|null $json_content
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UpdateLog whereJsonContent($value)
 * @property int $duration_in_seconds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UpdateLog whereDurationInSeconds($value)
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

    public function setUpdatedAt($value)
    {
        //
    }
}
