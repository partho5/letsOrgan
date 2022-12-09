<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestSubmittedAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_submitted_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedMediumInteger('user_id');
            $table->unsignedMediumInteger('contest_id');
            $table->string('started_at')->default(\Carbon\Carbon::now());
            $table->string('submitted_at')->nullable();
            $table->string('submitted_ans')->nullable();
            $table->string('submitted_attachment')->nullable();
            $table->float('obtained_mark')->default(0);
            $table->string('judged_by')->nullable();
            $table->boolean('banned')->default(0);
            $table->string('additional_info')->nullable();
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
        Schema::dropIfExists('contest_submitted_answers');
    }
}
