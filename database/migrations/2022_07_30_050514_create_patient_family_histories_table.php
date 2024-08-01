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
        Schema::create('patient_family_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_family_medical_history_id')->references('id')->on('patient_family_medical_histories')->onDelete('cascade');
            $table->foreignId('patient_relationship_id')->references('id')->on('patient_relationships')->onDelete('cascade');
            $table->integer('onset_age')->nullable();
            $table->integer('died')->nullable();
            $table->longText('note')->nullable();
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['patient_family_medical_history_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_family_histories');
    }
};
