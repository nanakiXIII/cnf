<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('titre');
            $table->longText('contenu');
            $table->longText('staff')->nullable();
            $table->longText('image')->nullable();
            $table->string('type');
            $table->string('etat');
            $table->string('slug');
            $table->integer('staff_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('serie_id')->unsigned()->index()->nullable();
            $table->integer('categorie_id')->unsigned()->index()->nullable();
            $table->boolean('publication')->default(false);
            $table->dateTime('publish_at')->nullable();
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
