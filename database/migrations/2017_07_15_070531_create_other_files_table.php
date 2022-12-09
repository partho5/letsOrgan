<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtherFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name');
            $table->string('file_type');
            $table->string('category');
            $table->string('description');
            $table->string('other_file'); //unnecessary for developer, but as 'other_file' exists in html form, Model and Database should have this field(perhaps), or throws error
            $table->string('file_path'); //url
            $table->string('uploaded_by'); //user_id
            $table->unsignedBigInteger('total_view')->default(0);
            $table->unsignedBigInteger('total_download')->default(0);
            $table->unsignedBigInteger('cloud_id'); //id in 'my_cloud' table
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
        Schema::dropIfExists('other_files');
    }
}
