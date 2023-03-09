<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminPlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_playlists', function (Blueprint $table) {
            $table->id();
            $table->string('playlist_title')->nullable();
            $table->string('playlist_title_slug')->nullable();
            $table->string('audio_language')->nullable();
            $table->string('audio_id')->nullable();
            $table->string('artist_id')->nullable();
            $table->string('album_id')->nullable(); 
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
        Schema::dropIfExists('admin_playlists');
    }
}
