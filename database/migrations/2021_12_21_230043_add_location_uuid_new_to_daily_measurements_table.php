<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationUuidNewToDailyMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_measurements', function (Blueprint $table) {
            $table->string('location_uuid_new')->nullable()->after('location_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_measurements', function (Blueprint $table) {
            $table->dropColumn('location_uuid_new');
        });
    }
}
