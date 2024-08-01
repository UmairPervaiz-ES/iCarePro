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
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_id')->references('id')->on('practices')->onDelete('cascade');
            $table->string('name');
            $table->double('price');
            $table->longText('description')->nullable();
            $table->string('created_by' , 20);
            $table->softDeletes();
            $table->string('updated_by' , 20)->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('procedures');
    }
};
