<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewLocationUuidsToStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('statistics', function (Blueprint $table) {
            $table->string('min_location_uuid_new')->nullable()->after('min_location_uuid');
            $table->string('max_location_uuid_new')->nullable()->after('max_location_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('statistics', function (Blueprint $table) {
            $table->dropColumn('min_location_uuid_new');
            $table->dropColumn('max_location_uuid_new');
        });
    }
}
