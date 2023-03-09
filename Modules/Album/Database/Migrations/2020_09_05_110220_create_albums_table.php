<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('album_name');
            $table->string('album_slug');
            $table->longtext('description')->nullable();
            $table->string('copyright')->nullable();
            $table->text('song_list');
            $table->bigInteger('listening_count')->default(0);
            $table->bigInteger('language_id')->default(0);
            $table->boolean('is_album_movie')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_trending')->default(0);
            $table->boolean('is_recommended')->default(0);
            $table->boolean('is_verified')->default(0); 
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('albums');
    }
}
