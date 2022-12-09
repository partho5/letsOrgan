<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contest_type');
            $table->unsignedMediumInteger('creator_id');
            $table->string('judge_id');
            $table->string('title')->default(null);
            $table->string('topic')->default(null);
            $table->string('reg_confirm_msg')->default(null);
            $table->string('additional_info')->nullable();
            $table->string('start_time');
            $table->string('end_time');
            $table->string('contest_policy')->default(null);
            $table->string('result_publish_time');
            $table->string('question_file_link')->nullable();
            $table->string('answer_file_link')->nullable();
            $table->string('reg_fee')->default(0);
            $table->string('total_marks')->default(0);
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
        Schema::dropIfExists('contest_informations');
    }
}
