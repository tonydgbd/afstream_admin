<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile')->nullable();
            $table->boolean('gender')->default(0);
            $table->integer('plan_id')->default(0);
            $table->string('purchased_plan_date')->nullable();
            $table->string('dob')->nullable();            
            $table->string('image')->nullable();
            $table->boolean('status')->default('1');
            $table->boolean('role')->default('0');
            $table->string('address')->nullable();
            $table->longtext('billing_detail')->nullable();
            $table->bigInteger('country_id')->unsigned()->nullable();
            $table->bigInteger('state_id')->unsigned()->nullable();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->string('braintree_id')->nullable();
            $table->string('pincode')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

  
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('users', function(Blueprint $table){
            $table->dropUnique('email');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
