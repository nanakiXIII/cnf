<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosteEquipe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postes', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('site');
        });

        Schema::create('postes_user', function(Blueprint $table){
            $table->increments('id');
            $table->integer('postes_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('postes_id')->references('id')->on('postes');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postes');
        Schema::dropIfExists('postes_user');
    }
}
