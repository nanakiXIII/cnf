<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjetEnCourTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avancements', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('episode');
            $table->integer('serie_id')->unsigned()->index();
            $table->integer('saison_id')->unsigned()->index();;
            $table->integer('encodage')->default(0);
            $table->integer('adapt')->default(0);
            $table->integer('edition')->default(0);
            $table->integer('qcheck')->default(0);
            $table->integer('traduction')->default(0);
            $table->integer('time')->default(0);
            $table->integer('check')->default(0);
            $table->string('type');
            $table->integer('user_id')->unsigned()->index();
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
