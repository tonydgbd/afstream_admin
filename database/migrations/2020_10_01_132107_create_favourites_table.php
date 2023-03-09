<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavouritesTable extends Migration
{
    public function up(){
        Schema::create('favourites', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->longText('album_id')->nullable();
            $table->longText('artist_id')->nullable();
            $table->longText('audio_id')->nullable();
            $table->longText('playlist_id')->nullable();
            $table->longText('genre_id')->nullable();
            $table->longText('user_language')->nullable();
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
        Schema::dropIfExists('favourites');
    }
}
