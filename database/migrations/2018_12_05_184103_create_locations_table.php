<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->string('uuid')->unique();
            $table->string('name');
            $table->string('postal_code');
            $table->unsignedInteger('measurement_node_id');
            $table->unsignedInteger('status_id');
            $table->integer('height');
            $table->float('longitude');
            $table->float('latitude');
            $table->float('last_measured_one_hour_value')->nullable();
            $table->timestamps();

            $table->primary('uuid');
            $table->foreign('measurement_node_id')->references('id')->on('measurement_nodes');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
