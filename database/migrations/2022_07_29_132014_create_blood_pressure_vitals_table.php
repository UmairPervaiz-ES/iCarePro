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
        Schema::create('blood_pressure_vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreignId('practice_id')->references('id')->on('practices')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->references('id')->on('appointments')->onDelete('cascade');
            $table->double('systole')->nullable();
            $table->double('diastole')->nullable();
            $table->enum('type' , 
            [  
                'sitting',
                'standing',
                'supine',
                'lying on side',
                'prone',
            ])->nullable();
            $table->enum('site' , 
            [  
                'L arm',
                'R arm',
                'L leg',
                'R leg',
                'L wrist',
                'R wrist',
            ])->nullable();
            $table->enum('cuffsize' , 
            [  
                'neonatal',
                'infant',
                'small pediatric',
                'pediatric',
                'small adult',
                'adult',
                'large adult',
                'child thigh',
                'adult thigh',
            ])->nullable();
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
        Schema::dropIfExists('blood_pressure_vitals');
    }
};
