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
        Schema::create('doctor_slot_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_slot_id')->constrained();
            $table->string('day' , 30);        //  saving day names e.g: Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday
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
        Schema::dropIfExists('doctor_slot_days');
    }
};
