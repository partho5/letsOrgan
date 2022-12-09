<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('community_name');
            $table->string('institute_name');
            $table->string('dept_name');
            $table->string('class_test_name')->default('Class Test');
            $table->string('student_per_batch'); //num of student per batch
            $table->string('academic_term'); //semester or year
            $table->string('description');
            $table->string('creator_id');
            $table->string('join_token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('communities');
    }
}
