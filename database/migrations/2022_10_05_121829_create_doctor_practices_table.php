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
        Schema::create('doctor_practices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained();
            $table->foreignId('practice_id')->constrained();
            $table->integer('role_id');
            $table->string('role_name','35');
            $table->boolean('doctor_status_in_practice')->default(1);   // 0 => account suspended in that particular practice, 1 => account active
            $table->boolean('currently_active_in_practice_status')->default(0);   // 0 => inactive, 1 => active
            $table->string('created_by' , 20);
            $table->string('updated_by' , 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_practices');
    }
};
