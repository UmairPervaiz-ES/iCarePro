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
        Schema::create('zoom_credentials', function (Blueprint $table) {
            $table->id();
            $table->longText('access_token')->nullable();
            $table->longText('refresh_token')->nullable();
            $table->dateTime('token_updated_at')->nullable();
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
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
        Schema::dropIfExists('zoom_credentials');
    }
};
