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
        Schema::create('consent_form_types', function (Blueprint $table) {
            $table->id();
            $table->enum('category',['PATIENT','DOCTOR'])->default('DOCTOR');
            $table->enum('sub_category',['REGISTRATION','APPOINTMENT'])->default('REGISTRATION');
            $table->string('type' , 50);
            $table->boolean('is_required')->default(0);
            $table->foreignId('practice_id')->references('id')->on('practices')->onDelete('cascade');
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
        Schema::dropIfExists('consent_form_types');
    }
};
