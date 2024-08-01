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
        Schema::create('patient_demographies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreignId('language_id')->nullable()->references('id')->on('languages')->onDelete('cascade')->nullable();
            $table->foreignId('race_id')->nullable()->references('id')->on('races')->onDelete('cascade');
            $table->foreignId('ethnicity_id')->nullable()->references('id')->on('ethnicities')->onDelete('cascade');
            $table->enum('marital_status', ['Unknown', 'Married', 'Single', 'Divorced',  'Separated', 'Widowed', 'Partner'])->nullable();
            $table->enum('sexual_orientation', ['Lesbian, gay or homosexual', 'Straight or heterosexual', 'Bisexual', 'Something else, please describe',  'Do not know', 'Choose not to disclose'])->nullable();
            $table->enum('gender_identity', ['Identifies as Male', 'Identifies as Female', 'Transgender Male/Female-to-Male (FTM)', 'Transgender Female/Male-to-Female (MTF)',  'Gender non-conforming (neither exclusively male nor female)', 'Additional gender category / other, please specify', 'Choose not to disclose'])->nullable();
            $table->enum('assigned_sex_at_birth', ['Male', 'Female', 'Choose not to disclose', 'unknown'])->nullable();
            $table->enum('pronoun', ['he/him', 'she/her', 'they/them', 'Choose not to disclose'])->nullable();
            $table->enum('home_bound', ['Yes', 'No'])->nullable();
            $table->integer('family_size')->nullable();
            $table->double('income')->nullable();
            $table->enum('income_define_per', ['Year', 'Month', '2 Weeks', 'Week', 'Hourly', 'Choose not to disclose'])->nullable();
            $table->string('agricultural_worker')->nullable();
            $table->string('homeless_status')->nullable();
            $table->string('school_based_health_center_patient')->nullable();
            $table->string('veteran_status')->nullable();
            $table->string('public_housing_patient')->nullable();
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['patient_id', 'ethnicity_id', 'gender_identity']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_demographies');
    }
};