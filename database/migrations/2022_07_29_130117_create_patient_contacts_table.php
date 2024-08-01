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
        Schema::create('patient_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('zip_code' , 15)->nullable();
            $table->foreignId('country_id')->nullable()->references('id')->on('countries')->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->references('id')->on('cities')->onDelete('cascade');
            $table->foreignId('state_id')->nullable()->references('id')->on('states')->onDelete('cascade');
            $table->string('home_country_code' , 10)->nullable();
            $table->string('home_phone_number' , 15)->nullable();
            $table->string('work_country_code' , 10)->nullable(); 
            $table->string('work_phone_number' , 15)->nullable();
            $table->enum('consent_to_text', ['Yes', 'No'])->nullable();
            $table->enum('contact_preference', ['Home phone', 'Work phone',  'Mail', 'Portal'])->nullable();
            $table->string('patient_email')->nullable();;
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['patient_id' ,'country_id', 'city_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_contacts');
    }
};
