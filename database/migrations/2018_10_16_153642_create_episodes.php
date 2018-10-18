<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->integer('numero');
            $table->string('type');
            $table->string('dvd');
            $table->string('hd');
            $table->string('fhd');
            $table->string('image')->default('noImage.jpg');
            $table->integer('serie_id')->unsigned()->index();
            $table->integer('saisons_id')->unsigned()->index();
            $table->string('etat');
            $table->string('streaming');
            $table->boolean('publication')->default(false);
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
        //
    }
}
