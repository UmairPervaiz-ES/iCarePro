<?php

use App\Models\Practice\InitialPractice;
use Carbon\Carbon;
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
        Schema::create('practice_registration_requests', function (Blueprint $table) {
            $table->id();
            $table->string('practice_name' , 50);
            $table->string('country_code' , 10);
            $table->string('phone_number', 15);
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('designation');
            $table->enum('status', ['Pending', 'Inreview','Accepted','Rejected'])->default('Pending');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['practice_name','first_name' ,'email']);
        });

        InitialPractice::create([
            'practice_name' => 'iCare Pro',
            'country_code'=>'+92',
            'phone_number'=>'1234567890',
            'first_name' => 'iCare',
            'last_name' => 'Pro',
            'middle_name'=>"Medical",
            'email'=> "practice@gmail.com",
            'designation'=> "admin",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        InitialPractice::create([
            'practice_name' => 'Shifa International',
            'country_code'=>'+92',
            'phone_number'=>'1234567890',
            'first_name' => 'Shifa',
            'last_name' => 'Hospital',
            'middle_name'=>"International",
            'email'=> "admin@icarepro.com",
            'designation'=> "admin",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practice_registration_requests');
    }
};
