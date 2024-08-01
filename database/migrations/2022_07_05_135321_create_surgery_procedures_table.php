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
        Schema::create('surgery_procedures', function (Blueprint $table) {
            $table->id();
            $table->string('surgery_name', 200);
            $table->string('has_laterality', 5);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['surgery_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surgery_procedures');
    }
};
