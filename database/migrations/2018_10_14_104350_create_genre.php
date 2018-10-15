<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenre extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genres', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('genres_serie', function(Blueprint $table){
            $table->increments('id');
            $table->integer('serie_id')->unsigned()->index();
            $table->integer('genres_id')->unsigned()->index();
            $table->foreign('serie_id')->references('id')->on('series')->onDelete('cascade');
            $table->foreign('genres_id')->references('id')->on('genres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('genres');
    }
}
