<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_rolls', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('roll_numeric');
            $table->string('roll_full_form')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedMediumInteger('community_id');
            $table->string('academic_session');
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
        Schema::dropIfExists('student_rolls');
    }
}
