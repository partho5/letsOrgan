<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VisitorTracker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_tracker', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedMediumInteger('user_id');
            $table->unsignedMediumInteger('community_id');
            $table->string('ip');
            $table->string('device_type');
            $table->string('device_name');
            $table->string('browser');
            $table->string('page_url');
            $table->timestamp('visit_time');
            $table->unsignedMediumInteger('session_duration'); //in seconds
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitor_tracker');
    }
}
