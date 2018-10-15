<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titre');
            $table->string('titre_original');
            $table->string('titre_alternatif');
            $table->year('annee');
            $table->string('studio');
            $table->string('auteur');
            $table->integer('episode')->unsigned()->default(0);
            $table->integer('oav')->unsigned()->default(0);
            $table->integer('films')->unsigned()->default(0);
            $table->integer('bonus')->unsigned()->default(0);
            $table->integer('scan')->unsigned()->default(0);
            $table->integer('ln')->unsigned()->default(0);
            $table->integer('vn')->unsigned()->default(0);
            $table->longText('synopsis');
            $table->longText('staff');
            $table->string('type')->default('Animes');
            $table->boolean('publication')->default(false);
            $table->string('slug');
            $table->string('image')->default('noImage.jpg');
            $table->integer('coprod')->unsigned()->default(0);
            $table->timestamps();
        });
        Schema::create('serie_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('serie_id')->unsigned()->default(0)->index();
            $table->integer('user_id')->unsigned()->default(0)->index();
            $table->timestamps();
            $table->foreign('serie_id')->references('id')->on('series')->onDelete('cascade');
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
        Schema::dropIfExists('series');
        Schema::dropIfExists('serie_user');
    }
}