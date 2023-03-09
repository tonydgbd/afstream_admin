<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('audio');
            $table->string('audio_duration')->nullable();
            $table->boolean('aws_upload')->default(0);
            $table->boolean('is_external')->default(0);
            $table->boolean('external_url')->nullable();
            $table->string('audio_title');
            $table->string('audio_slug');
            $table->bigInteger('audio_genre_id');
            $table->text('artist_id');
            $table->string('audio_language');
            $table->string('copyright')->nullable();
            // $table->boolean('is_paid')->default(0);
            // $table->integer('amount')->default(0);
            $table->bigInteger('listening_count')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_trending')->default(0);
            $table->boolean('is_recommended')->default(0);
            $table->boolean('status')->default(0);
            $table->longtext('lyrics')->nullable();
            $table->text('description')->nullable();
            $table->string('release_date')->nullable();
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
        Schema::dropIfExists('audio');
    }
}
