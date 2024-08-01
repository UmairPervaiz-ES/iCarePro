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
        Schema::create('specializations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->softDeletes();
            $table->timestamps();
            $table->index('name');
        });

        \App\Models\Doctor\Specialization::create([
            'name' => 'Dermatology',
        ]);
        \App\Models\Doctor\Specialization::create([
            'name' => 'Neurology',
        ]);
        \App\Models\Doctor\Specialization::create([
            'name' => 'Internal medicine',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specializations');
    }
};
