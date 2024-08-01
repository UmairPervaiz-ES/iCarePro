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
        Schema::create('consent_form_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consent_form_type_id')->references('id')->on('consent_form_types')->onDelete('cascade');
            $table->foreignId('consent_form_id')->references('id')->on('consent_forms')->onDelete('cascade');
            $table->enum('consent_status', ['AGREE', 'DISAGREE']);
            $table->enum('category', ['DOCTOR', 'PATIENT']);
            $table->string('category_id');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['consent_form_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consent_form_logs');
    }
};
