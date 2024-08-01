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
        Schema::create('patient_allergy_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_allergy_id')->references('id')->on('patient_allergies')->onDelete('cascade');
            $table->foreignId('reaction_id')->references('id')->on('reactions')->onDelete('cascade');
            $table->enum('reaction_severity', ['Mild', 'Moderate', 'Severe'])->nullable();
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['patient_allergy_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_allergy_reactions');
    }
};
