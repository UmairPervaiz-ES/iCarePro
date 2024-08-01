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
        Schema::create('user_outlook_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('user_email')->nullable();
            $table->longText('access_token')->nullable();
            $table->string('expires_in')->nullable();
            $table->string('scope')->nullable();
            $table->string('token_type')->nullable();
            $table->longText('refresh_token')->nullable();
            $table->longText('id_token')->nullable();
            $table->dateTime('token_updated_at')->nullable();
            $table->dateTime('token_updated_by_patient')->nullable();
            $table->string('url')->nullable();
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
        Schema::dropIfExists('user_outlook_calendars');
    }
};
