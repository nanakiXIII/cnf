<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFichiersNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes_post', function(Blueprint $table){
            $table->increments('id');
            $table->integer('post_id')->unsigned()->index();
            $table->integer('episodes_id')->unsigned()->index();
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('episodes_id')->references('id')->on('episodes')->onDelete('cascade');
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
