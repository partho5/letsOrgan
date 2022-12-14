<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeptCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dept_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('course_code');
            $table->string('course_name');
            $table->text('about_course');
            $table->mediumInteger('possessed_by_community');
            $table->mediumInteger('data_added_by');
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
        Schema::dropIfExists('dept_courses');
    }
}
