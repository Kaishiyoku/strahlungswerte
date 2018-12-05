<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_measurements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('location_uuid');
            $table->float('value')->nullable();
            $table->timestamp('date');

            $table->foreign('location_uuid')->references('uuid')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_measurements');
    }
}
