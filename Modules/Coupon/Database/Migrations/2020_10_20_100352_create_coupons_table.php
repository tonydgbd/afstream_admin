<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->boolean('discount_type')->default(0)->comment = '1=dollar, 2=percentage';
            $table->bigInteger('discount');
            $table->string('coupon_code');
            $table->text('description')->nullable();
            $table->integer('coupon_used_count')->default(1);
            $table->string('starting_date');
            $table->string('expiry_date');
            $table->integer('applicable_on')->comment = '0=all_section, 1=particular_plan';
            $table->longtext('plan_id')->nullable();
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
        Schema::dropIfExists('coupons');
    }
}
