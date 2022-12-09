<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExamQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('course_id');
            $table->unsignedSmallInteger('year');
            $table->string('question_of'); // final/ class test exam
            $table->string('file_name');
            $table->text('file_path');
            $table->unsignedSmallInteger('file_size');
            $table->unsignedBigInteger('cloud_id');
            $table->unsignedMediumInteger('uploaded_by');
            $table->unsignedMediumInteger('total_view')->default(0);
            $table->unsignedMediumInteger('total_download')->default(0);
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
        Schema::dropIfExists('exam_questions');
    }
}
