<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('all_countries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->char('iso',2);
            $table->string('name',80);
            $table->string('nicename',80);
            $table->char('iso3',3)->nullable();
            $table->smallInteger('numcode');
            $table->integer('phonecode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_countries');
    }
}
