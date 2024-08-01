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
        Schema::create('prescribed_drugs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreignId('practice_id')->references('id')->on('practices')->onDelete('cascade');
            $table->foreignId('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->foreignId('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->foreignId('medical_problem_id')->nullable()->references('id')->on('medical_problems')->onDelete('cascade');
            $table->foreignId('strength_id')->nullable()->references('id')->on('drug_strengths')->onDelete('cascade');
            $table->string('strength_value' , 20)->nullable();            
            $table->foreignId('drug_id')->nullable()->references('id')->on('drugs')->onDelete('cascade');
            $table->string('drug_name' , 150)->nullable();            
            $table->string('type' , 50)->nullable();            
            $table->integer('quantity')->nullable();
            $table->enum('mg_tab' , 
            [  
                'mg',
                'tablet(s)',
            ])->nullable();
            $table->enum('repetition' , 
            [  
                'every day',
                'twice a day', 
                '3 times a day', 
                '4 times a day', 
                '5 times a day', 
                '6 times a day', 
                'every other day', 
                'every hour', 
                'every 2 hours', 
                'every 3 hours', 
                'every 3-4 hours', 
                'every 4 hours', 
                'every 4-6 hours', 
                'every 6 hours', 
                'every 6-8 hours', 
                'every 8 hours', 
                'every 12 hours', 
                'every 24 hours', 
                'every 72 hours', 
                'every week', 
                'twice a week', 
                '3 times a week', 
                'every 2 weeks', 
                'every 3 weeks', 
                'every 4 weeks', 
                'every month', 
                'every 2 months', 
                'every 3 months', 
                'as needed',
            ])->nullable();
            $table->enum('route' , 
            [  
                'oral',
                'Inject',
                'Physical',
            ])->nullable();
            $table->enum('when' , 
            [  
                'before meals',
                'with meals',
                'after meals',
                'in the morning',
                'at noon',
                'in the evening',
                'at dinner',
                'at bedtime',
                'around the clock',
                'as directed',
                'as needed',
            ])->nullable();
            $table->enum('quantity_unit' , 
            [  
                'tablet(s)',
                'mg',
                'blist pack(s) of 100',
                'bottle(s) of 100',
                'bottle(s) of 1000',
            ])->nullable();
            $table->date('earliest_fill_date')->nullable();
            $table->integer('for_days')->nullable();
            $table->integer('quantity_total')->nullable();
            $table->longText('note_to_pharmacy')->nullable();
            $table->longText('note_to_patient')->nullable();
            $table->longText('internal_note')->nullable();
            $table->boolean('dispense_as_written')->default(false);
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
        Schema::dropIfExists('prescribed_drugs');
    }
};
