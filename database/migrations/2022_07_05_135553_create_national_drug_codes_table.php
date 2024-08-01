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
        Schema::create('national_drug_codes', function (Blueprint $table) {
            $table->id();
            $table->string('ndc')->nullable();
            $table->string('name')->nullable();
            $table->string('value')->nullable();
            $table->string('formatted_ndc')->nullable();
            $table->string('label_name')->nullable();
            $table->string('description')->nullable();
            $table->string('obsolete_date')->nullable();
            $table->timestamps();
            $table->index(['ndc', 'name']);
                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('national_drug_codes');
    }
};
