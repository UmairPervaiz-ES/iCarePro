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
        Schema::create('weight_vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreignId('practice_id')->references('id')->on('practices')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->references('id')->on('appointments')->onDelete('cascade');
            $table->double('weight_lbs')->nullable();
            $table->enum('type' , 
            [  
                'Stated',
                'Dry',
                'Preoperative',
                'With clothes',
                'Without clothes',
                'First',
            ])->nullable();
            $table->enum('weight_prepost' , 
            [  
                'Pre-dialysis',
                'Post-dialysis',
            ])->nullable();
            $table->boolean('out_of_range')->default(false);
            $table->boolean('not_performed')->default(false);
            $table->enum('reason' , 
            [  
                'Not indicated',
                'Not tolerated',
                'Patient refused',
            ])->nullable(); 
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
        Schema::dropIfExists('weight_vitals');
    }
};
