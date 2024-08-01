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
        Schema::create('doctor_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained();
            $table->foreignId('practice_id')->constrained();
            $table->date('date_from');
            $table->date('date_to');
            $table->time('time_from');      // 4 pm
            $table->time('time_to');        // 7 pm
            $table->enum('timezone', ['Hawaii-Aleutian time', 'Alaska time', 'Pacific time', 'Mountain time', 'Central time', 'Eastern time'])->nullable();
            $table->integer('slot_time');        // e.g 20 mints, 30 mints
            $table->boolean('status')->default(0);      // 0 for in-active, 1 for active
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['doctor_id', 'date_from', 'date_to', 'time_from', 'time_to']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_slots');
    }
};
