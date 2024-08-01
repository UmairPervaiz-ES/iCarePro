<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_key' , 20)->uniqid();
            $table->foreignId('practice_id')->references('id')->on('practices')->onDelete('cascade');
            $table->foreignId('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->foreignId('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->string('medical_problem_id')->nullable();
            $table->foreignId('doctor_slot_id')->references('id')->on('doctor_slots')->onDelete('cascade');
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('utc_date')->nullable();
            $table->time('utc_start_time')->nullable();
            $table->time('utc_end_time')->nullable();
            $table->string('reason')->nullable();
            $table->string('comments')->nullable();
            $table->enum('status', ['Pending', 'Checked in' ,'Confirmed','Cancelled','Completed','Rescheduled'])->default('Pending');
            $table->enum('timezone', ['Hawaii-Aleutian time', 'Alaska time', 'Pacific time', 'Mountain time', 'Central time', 'Eastern time'])->default('Alaska time');
            $table->longText('instructions')->nullable();
            $table->bigInteger('previous_id')->nullable();
            $table->string('created_by' , 20)->nullable();
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
        Schema::dropIfExists('appointments');
    }
};
