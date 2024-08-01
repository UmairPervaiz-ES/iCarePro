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
        Schema::create('patient_medical_problems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreignId('medical_problem_id')->references('id')->on('medical_problems')->onDelete('cascade');
            $table->enum('status', ['Active', 'Historical'])->nullable();
            $table->string('removal_reason')->nullable();
            $table->enum('type', ['Chronic', 'Acute'])->nullable();
            $table->date('onset_date')->nullable();
            $table->date('last_occurrence')->nullable();
            $table->longText('note')->nullable();
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['patient_id', 'medical_problem_id', 'status','type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_medical_problems');
    }
};
