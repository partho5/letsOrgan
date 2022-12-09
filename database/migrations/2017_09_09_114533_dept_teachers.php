<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeptTeachers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dept_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('course_id');
            $table->string('year');
            $table->string('teacher_name');
            $table->unsignedInteger('possessed_by_community');
            $table->unsignedMediumInteger('data_added_by');
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
        Schema::dropIfExists('dept_teachers');
    }
}
