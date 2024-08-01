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
        Schema::create('practice_billing_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_id')->references('id')->on('practices')->onDelete('cascade');
            $table->string('billing_address_line_1');
            $table->string('billing_address_line_2')->nullable();
            $table->integer('billing_country_id')->nullable();
            $table->integer('billing_city_id')->nullable();
            $table->integer('billing_state_id')->nullable();
            $table->string('billing_zip_code' ,15)->nullable();
            $table->decimal('billing_lat', 10, 8)->nullable();
            $table->decimal('billing_lng', 11, 8)->nullable();
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['billing_address_line_1']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practice_billing_addresses');
    }
};
