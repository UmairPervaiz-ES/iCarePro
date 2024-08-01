<?php

use App\Models\Subscription\Subscription;
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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name' , 40);
            $table->longText('description');
            $table->integer('duration_days');
            $table->float('price');
            $table->integer('allowed_doctors');
            $table->integer('allowed_staff');
            $table->integer('allowed_appointments');
            $table->integer('allowed_patients');
            $table->boolean('is_trial')->default('0');
            $table->boolean('status');
            $table->string('created_by' , 20);
            $table->string('updated_by' , 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['name', 'duration_days' ,'price']);
        });
        Subscription::create([
            'name' => 'ICarePro',
            'description' => 'icareproai',
            'duration_days' => 1,
            'price' => 20.00,
            'allowed_appointments'=>0,
            'allowed_doctors' => 10,
            'allowed_staff' => 10,
            'allowed_patients' => 10,
            'status' => 0,
            'created_by'=>1,
            'updated_by'=>1,

            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
