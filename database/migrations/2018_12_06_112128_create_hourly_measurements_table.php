<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHourlyMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hourly_measurements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('location_uuid');
            $table->unsignedInteger('inspection_status')->nullable();
            $table->float('value')->nullable();
            $table->float('precipitation_probability')->nullable();
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
        Schema::dropIfExists('hourly_measurements');
    }
}
