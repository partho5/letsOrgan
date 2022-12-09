<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('book_name');
            $table->string('author');
            $table->string('category');
            $table->string('description');
            $table->string('book_file'); //unnecessary for developer, but as 'book_file' exists in html form, Model and Database should have this field(perhaps), or throws error
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
        Schema::dropIfExists('books');
    }
}
