<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpdateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('update_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_successful');
            $table->string('command_name');
            $table->integer('number_of_new_entries')->default(0);
            $table->integer('duration_in_seconds');
            $table->longText('json_content')->nullable();

            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('update_logs');
    }
}
