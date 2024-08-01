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
        Schema::create('medical_problems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('has_laterality' , 5)->nullable();
            $table->string('is_diagnosable' , 5)->nullable();
            $table->boolean('is_active')->default(TRUE);
            $table->softDeletes();
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
        Schema::dropIfExists('medical_problems');
    }
};
