<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_transactions', function (Blueprint $table) {
           $table->id();
           $table->string('charge_id' , 50)->unique();
           $table->foreignId('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
           $table->foreignId('practice_id')->references('id')->on('practices')->onDelete('cascade');
           $table->foreignId('subscription_discount_id')->nullable()->references('id')->on('subscription_discounts')->onDelete('cascade');
           $table->string('card_id' , 50)->nullable();
           $table->string('customer_id' , 50)->nullable();
           $table->double('amount_paid');
           $table->double('actual_amount');
           $table->boolean('status');
           $table->string('created_by' , 20);
           $table->string('updated_by' , 20)->nullable();
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
        Schema::dropIfExists('subscription_transactions');
    }
};
