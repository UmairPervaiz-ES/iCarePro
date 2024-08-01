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
        Schema::create('patient_privacies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->boolean('notice')->nullable();
            $table->boolean('consent_call')->nullable();
            $table->longText('patient_notes')->nullable();
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['patient_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_privacies');
    }
};
