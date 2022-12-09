<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMyCloudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_cloud', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('name'); //directory or file name
            $table->string('parent_dir');
            $table->boolean('is_root'); //weather the directory is in root layer
            $table->string('full_dir_url');
            $table->string('file_ext'); //file extension
            $table->smallInteger('access_code')->default(3); //0=only me, 1=shared with particular user, 2=particular community,  3=public
            $table->unsignedMediumInteger('possessed_by_community')->default(0); // although theres no community with id 0
            $table->unsignedInteger('file_size'); //save in KB
            $table->boolean('soft_delete')->default(0);
            $table->boolean('permanent_delete')->default(0);
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
        Schema::dropIfExists('my_cloud');
    }
}
