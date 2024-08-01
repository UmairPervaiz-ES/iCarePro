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
        Schema::create('patient_identifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->string('legal_first_name' , 30);
            $table->string('legal_last_name' , 30)->nullable();
            $table->string('legal_middle_name' , 30)->nullable();
            $table->enum('suffix', ['Miss', 'Mrs', 'Ms', 'Mr'])->nullable();
            $table->enum('legal_sex', ['Male', 'Female', 'Other'])->nullable();
            $table->string('previous_name' , 50)->nullable();
            $table->date('dob')->nullable();
            $table->string('emirates_id' , 17)->nullable();
            $table->string('mother_name' , 50)->nullable();
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['patient_id', 'emirates_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_identifications');
    }
};
