<?php

use App\Models\Status;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->unsignedInteger('id')->unique();
            $table->string('name')->unique();

            $table->primary('id');
            $table->index('name');
        });

        // create statuses
        $statuses = getKeyValuePairsFromStr(env('ODL_STATUSES'));

        foreach ($statuses as $key => $value) {
            $status = new Status();
            $status->id = $key;
            $status->name = $value;

            $status->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
