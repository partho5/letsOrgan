<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UploadRequestResponse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_request_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('request_id');
            $table->unsignedInteger('uploaded_by');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('soft_delete')->default(0);
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
        Schema::dropIfExists('upload_request_responses');
    }
}
