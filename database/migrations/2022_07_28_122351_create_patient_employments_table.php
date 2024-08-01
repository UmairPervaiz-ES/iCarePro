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
        Schema::create('patient_employments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->string('occupation', 50)->nullable();
            $table->string('employer_name' , 30);
            $table->string('employer_address')->nullable();
            $table->string('industry')->nullable();
            $table->string('zip_code' , 15)->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->string('country_code' , 10)->nullable();
            $table->string('phone' , 15)->nullable();
            $table->string('email')->nullable();
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['patient_id', 'occupation', 'employer_name', 'phone']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_employments');
    }
};
