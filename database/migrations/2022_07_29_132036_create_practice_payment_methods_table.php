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
        Schema::create('practice_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_id')->references('id')->on('practices')->onDelete('cascade');
            $table->string('card_holder_name');
            $table->string('card_id' , 50);
            $table->string('customer_id' , 50);
            $table->string('brand' , 30)->nullable();
            $table->string('country' , 50)->nullable();
            $table->string('cvc_check' , 30)->nullable();
            $table->string('dynamic_last4' , 10)->nullable();
            $table->string('exp_month' , 20)->nullable();
            $table->string('exp_year' , 20)->nullable();
            $table->string('fingerprint' , 50)->nullable();
            $table->string('funding' , 50)->nullable();
            $table->string('last4' , 10)->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_country')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line1_check')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_zip')->nullable();
            $table->string('address_zip_check')->nullable();
            $table->string('tokenization_method')->nullable();
            $table->boolean('default_payment_method');
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
        Schema::dropIfExists('practice_payment_methods');
    }
};
