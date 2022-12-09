<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllCommunitiesOfAUserTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_communities_of_a_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('community_id')->default(0); //if somehow community_id cannt be assigned, default value 0 will indicate there's no community, because no community_id starts with 1
            $table->string('academic_session')->nullable()->default(null);
            $table->string('role')->length(15);
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
        Schema::dropIfExists('all_communities_of_a_user');
    }
}
