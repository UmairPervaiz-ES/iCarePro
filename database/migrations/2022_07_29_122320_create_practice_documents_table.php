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
        Schema::create('practice_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_registration_request_id');
            $table->foreign('practice_registration_request_id')->references('id')->on('practice_registration_requests')->onDelete('cascade');
            $table->string('name' , 50)->nullable();
            $table->string('file_path')->nullable();
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['name']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practice_documents');
    }
};
