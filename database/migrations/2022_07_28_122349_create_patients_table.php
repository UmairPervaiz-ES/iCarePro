<?php

use App\Models\Patient\Patient;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_key' , 20)->nullable();
            $table->string('country_code' , 10);
            $table->string('phone_number' , 15)->unique();
            $table->string('first_name' , 30);
            $table->string('last_name' , 30);
            $table->string('middle_name' , 30)->nullable();
            $table->string('email');
            $table->enum('gender', ['Male', 'Female', 'Transgender', 'Prefer not to say', 'Other']);
            $table->date('dob')->nullable();
            $table->boolean('is_phone_number_verified')->default('0');
            $table->string('profile_photo_url')->nullable();
            $table->string('thumbnail_photo_url')->nullable();
            $table->string('password')->nullable();
            $table->boolean('is_password_reset')->default('0');
            $table->boolean('is_first_login')->default('0');
            $table->string('created_by' , 20)->nullable();
            $table->string('updated_by' , 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->rememberToken();

            $table->index(['phone_number' ,'email', 'country_code', 'is_phone_number_verified', 'is_first_login']);
        });

        Patient::create([
            'patient_key' => 'patient-1',
            'country_code' => '+1',
            'phone_number' => '8332403627',
            'first_name' => 'patient',
            'last_name' => 'test',
            'email' => "patient@gmail.com",
            'password' => Hash::make('123456789'), // <---- check this
            'gender' => "Male",
            'dob' => \Carbon\Carbon::now(),
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
        Schema::dropIfExists('patients');
    }
};
