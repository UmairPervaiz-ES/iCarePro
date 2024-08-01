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
        Schema::create('doctor_practice_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained();
            $table->foreignId('practice_id')->constrained();
            $table->unsignedInteger('count')->default(1);
            $table->enum('status', ['Pending', 'Accepted', 'Rejected'])->default('Pending');  // practice sends request to doctor
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
        Schema::dropIfExists('doctor_practice_requests');
    }
};
