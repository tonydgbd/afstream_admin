<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisementsTable extends Migration
{
    public function up(){
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longtext('google_adsense_script')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

   
    public function down(){
        Schema::dropIfExists('advertisements');
    }
}
