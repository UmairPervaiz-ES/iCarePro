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
        Schema::create('patient_relationships', function (Blueprint $table) {
            $table->id();
            $table->string('relationship' , 30);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['relationship']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_relationships');
    }
};
