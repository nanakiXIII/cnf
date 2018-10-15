<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaisons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saisons', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->integer('numero');
            $table->string('type');
            $table->integer('serie_id')->unsigned()->index();
            $table->boolean('publication')->default(false);
            $table->boolean('nosaison')->default(false);
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
        Schema::dropIfExists('saisons');
    }
}
