<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_discounts', function (Blueprint $table) {
           $table->id();
           $table->foreignId('subscription_id')->references('id')->on('subscriptions');
           $table->double('plan_discount_percent');
           $table->dateTime('plan_discount_till');
           $table->boolean('status');
           $table->string('created_by' , 20);
           $table->string('updated_by' , 20)->nullable();
           $table->timestamps();
           $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_discounts');
    }
};
