<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuccessPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('success_payments', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->bigInteger('user_id');
            $table->longtext('plan_id');
            $table->longtext('payment_data');
            $table->string('order_id')->nullable();
            $table->boolean('status')->comment = '0=fail, 1=success';
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
        Schema::dropIfExists('success_payments');
    }
}
