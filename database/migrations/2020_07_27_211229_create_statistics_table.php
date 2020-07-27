<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('number_of_operational_locations');
            $table->float('average_value')->nullable();
            $table->string('min_location_uuid');
            $table->float('min_value')->nullable();
            $table->string('max_location_uuid');
            $table->float('max_value')->nullable();

            $table->foreign('min_location_uuid')->references('uuid')->on('locations');
            $table->foreign('max_location_uuid')->references('uuid')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics');
    }
}
