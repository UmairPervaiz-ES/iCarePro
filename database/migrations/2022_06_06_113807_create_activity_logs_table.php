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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->longText('endpoint');
            $table->bigInteger('user_id')->nullable();
            $table->string('type' , 18)->nullable();
            $table->longText('message')->nullable();
            $table->longText('user_agent')->nullable();
            $table->string('ip', 25)->comment('server_ip')->nullable();
            $table->string('browser_ip' , 25)->nullable();
            $table->string('method' , 10)->nullable();
            $table->longText('raw_request')->nullable();
            $table->longText('raw_response')->nullable();
            $table->text('browser')->nullable();
            $table->text('platform')->nullable();
            $table->string('device_name' , 50)->nullable();
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
        Schema::dropIfExists('activity_logs');
    }
};
