<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('plan_name');
            $table->integer('plan_amount')->default(0);
            $table->integer('is_month_days')->default(0)->comment = '0=days, 1=months';
            $table->integer('validity')->default(0);
            $table->integer('is_download')->default(0);
            $table->integer('show_advertisement')->default(0);
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('plans');
    }
}
