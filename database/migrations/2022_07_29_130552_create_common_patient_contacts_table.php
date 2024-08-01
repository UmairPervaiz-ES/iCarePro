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
        Schema::create('common_patient_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->enum('patient_relationship', ['Spouse', 'Parent', 'Child', 'Sibling' ,'Friend','Cousin','Guardian','Other'])->default('Other');
            $table->string('first_name' , 30);
            $table->string('middle_name' , 30)->nullable();
            $table->string('last_name' , 30);
            $table->string('email')->nullable();
            $table->enum('suffix', ['Miss', 'Mrs', 'Ms', 'Mr'])->nullable();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->foreignId('country_id')->nullable()->references('id')->on('countries')->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->references('id')->on('cities')->onDelete('cascade');
            $table->foreignId('state_id')->nullable()->references('id')->on('states')->onDelete('cascade');
            $table->string('zip_code' , 15)->nullable();
            $table->string('emirates_id' , 17)->nullable();
            $table->string('country_code' , 10)->nullable();
            $table->string('phone' ,  15)->nullable();
            $table->enum('contact_reference', ['guarantor', 'guardian', 'next to kin', 'emergency contact']);
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['first_name', 'patient_id', 'country_code', 'last_name', 'emirates_id', 'email', 'phone']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('common_patient_contacts');
    }
};
