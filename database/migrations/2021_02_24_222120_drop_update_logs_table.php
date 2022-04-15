<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('update_logs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
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
};
