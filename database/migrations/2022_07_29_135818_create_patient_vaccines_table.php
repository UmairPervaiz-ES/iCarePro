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
        Schema::create('patient_vaccines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreignId('vaccine_id')->nullable()->references('id')->on('vaccines')->onDelete('cascade');
            $table->foreignId('route_id')->nullable()->references('id')->on('routes')->onDelete('cascade');
            $table->foreignId('national_drug_code_id')->nullable()->references('id')->on('national_drug_codes')->onDelete('cascade');
            $table->foreignId('site_id')->nullable()->references('id')->on('sites')->onDelete('cascade');
            $table->foreignId('manufacture_id')->nullable()->references('id')->on('manufactures')->onDelete('cascade');
            $table->date('administer_date')->nullable();
            $table->string('administer_by')->nullable();
            $table->double('amount')->nullable();
            $table->enum('unit', ['ml', 'mcg', 'mg', 'capsule'])->nullable();
            $table->string('lot_number' , 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('vaccine_given_date')->nullable();
            $table->date('date_on_vaccine')->nullable();
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['patient_id', 'vaccine_id', 'manufacture_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_vaccines');
    }
};
