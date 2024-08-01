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
        Schema::create('patient_social_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->enum('gender_identity', ['Identifies as Male', 'Identifies as Female', 'Transgender Male/Female-to-Male (FTM)', 'Transgender Female/Male-to-Female (MTF)' ,'Gender non-conforming (neither exclusively male nor female)','Additional gender category / other, please specify','Choose not to disclose'])->nullable();
            $table->enum('sex_at_birth', ['Male', 'Female', 'Choose not to disclose', 'unknown'])->nullable();
            $table->enum('pronoun', ['he/him', 'she/her', 'they/them'])->nullable();
            $table->string('first_name')->nullable();
            $table->enum('sexual_orientation', ['Lesbian, gay or homosexual', 'Straight or heterosexual', 'Bisexual','Something else, please describe','Do not know','Choose not to disclose'])->nullable();
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
        Schema::dropIfExists('patient_social_histories');
    }
};
