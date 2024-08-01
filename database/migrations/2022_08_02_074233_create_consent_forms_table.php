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
        Schema::create('consent_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consent_form_type_id')->references('id')->on('consent_form_types')->onDelete('cascade');
            $table->foreignId('practice_id')->references('id')->on('practices')->onDelete('cascade');
            $table->enum('publish_status',['PENDING','ACTIVE','DEACTIVATE'])->default('PENDING');
            $table->string('version' , 20)->default(1.0);
            $table->longText('content')->nullable();
            $table->longText('content_arabic')->nullable();
            $table->enum('content_status', ['DRAFT', 'SAVE']);
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->dateTime('published_at')->nullable();
            $table->dateTime('deactivated_at')->nullable();
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
        Schema::dropIfExists('consent_forms');
    }
};
