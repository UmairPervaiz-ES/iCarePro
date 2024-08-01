<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('medical_problems', function (Blueprint $table) {
            $table->boolean('is_complain')->nullable()->default(0);
        });

        DB::update("update medical_problems set is_complain = true where id > 21015");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_problems', function (Blueprint $table) {
            $table->dropColumn('is_complain');
        });
    }
};
