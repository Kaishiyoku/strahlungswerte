<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePrecisionOfValueFieldsForMeasurementTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_measurements', function (Blueprint $table) {
            $table->float('value', 10, 4)->nullable()->change();
        });

        Schema::table('hourly_measurements', function (Blueprint $table) {
            $table->float('value', 10, 4)->nullable()->change();
            $table->float('precipitation_probability', 10, 4)->nullable()->change();
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
            $table->float('value')->nullable()->change();
        });

        Schema::table('hourly_measurements', function (Blueprint $table) {
            $table->float('value')->nullable()->change();
            $table->float('precipitation_probability')->nullable()->change();
        });
    }
}
