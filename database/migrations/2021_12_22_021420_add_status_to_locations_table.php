<?php

use App\Enums\LocationStatus;
use App\Models\Location;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->enum('status', LocationStatus::getValues())->nullable()->after('status_id');
        });

        // map to new statuses
        $newStatusMapping = [
            0 => LocationStatus::Faulty,
            1 => LocationStatus::Operational,
            128 => LocationStatus::TestMode,
            2048 => LocationStatus::TestMode,
        ];

        Location::all()->each(function (Location $location) use ($newStatusMapping) {
            $location->status = Arr::get($newStatusMapping, $location->status_id);
            $location->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
