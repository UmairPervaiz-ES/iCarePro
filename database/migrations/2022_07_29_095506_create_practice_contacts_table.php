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
        Schema::create('practice_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_registration_request_id');
            $table->foreign('practice_registration_request_id')->references('id')->on('practice_registration_requests')->onDelete('cascade');
            $table->string('country_code' , 10)->nullable();
            $table->string('phone_number' , 15)->nullable();
            $table->string('first_name' , 30)->nullable();
            $table->string('middle_name' , 30)->nullable();
            $table->string('last_name' , 30)->nullable();
            $table->string('email')->nullable();
            $table->string('designation')->nullable();
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['phone_number' ,'first_name', 'last_name'  ,'email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practice_contacts');
    }
};
