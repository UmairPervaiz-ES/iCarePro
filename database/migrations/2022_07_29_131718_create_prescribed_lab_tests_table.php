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
        Schema::create('prescribed_lab_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreignId('practice_id')->references('id')->on('practices')->onDelete('cascade');
            $table->foreignId('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->foreignId('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->foreignId('lab_test_id')->nullable()->references('id')->on('lab_tests')->onDelete('cascade');
            $table->foreignId('medical_problem_id')->nullable()->references('id')->on('medical_problems')->onDelete('cascade');
            $table->string('lab_test_name');            
            $table->string('created_by' , 20);
            $table->string('updated_by' , 20)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('prescribed_lab_tests');
    }
};
