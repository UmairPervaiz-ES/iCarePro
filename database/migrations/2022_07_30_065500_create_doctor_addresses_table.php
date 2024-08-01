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
        Schema::create('doctor_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained();

            $table->foreignId('current_country_id')->nullable()->references('id')->on('countries');
            $table->foreignId('current_state_id')->nullable()->references('id')->on('states');
            $table->foreignId('current_city_id')->nullable()->references('id')->on('cities');

            $table->foreignId('home_town_country_id')->nullable()->references('id')->on('countries');
            $table->foreignId('home_town_state_id')->nullable()->references('id')->on('states');
            $table->foreignId('home_town_city_id')->nullable()->references('id')->on('cities');

            $table->string('current_address_1')->nullable();
            $table->string('current_address_2')->nullable();
            $table->string('current_zip_code')->nullable();
            $table->string('home_town_address_1')->nullable();
            $table->string('home_town_address_2')->nullable();
            $table->string('home_town_zip_code')->nullable();
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['doctor_id', 'current_country_id', 'current_city_id', 'current_zip_code',]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_addresses');
    }
};
