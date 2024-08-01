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
        Schema::create('template_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_id')->constrained()->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('country_code')->nullable();
            $table->longText('disclaimer')->nullable();
            $table->string('logo')->nullable();
            $table->string('color_scheme')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['practice_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template_data');
    }
};
