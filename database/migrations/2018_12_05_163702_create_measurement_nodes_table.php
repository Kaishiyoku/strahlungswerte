<?php

use App\Models\MeasurementNode;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeasurementNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurement_nodes', function (Blueprint $table) {
            $table->unsignedInteger('id')->unique();
            $table->string('name')->unique();

            $table->primary('id');
            $table->index('name');
        });

        // create measurement nodes
        $measurementNodes = getKeyValuePairsFromStr(config('odl.measurement_nodes'));

        foreach ($measurementNodes as $key => $value) {
            $measurementNode = new MeasurementNode();
            $measurementNode->id = $key;
            $measurementNode->name = $value;

            $measurementNode->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measurement_nodes');
    }
}
