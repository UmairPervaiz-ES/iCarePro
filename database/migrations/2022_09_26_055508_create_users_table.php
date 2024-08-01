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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_id')->constrained();
            $table->foreignId('department_id')->constrained();
            $table->foreignId('department_employee_type_id')->nullable();
            $table->foreignId('current_country_id');
            $table->foreign('current_country_id')->references('id')->on('countries');
            $table->foreignId('current_state_id');
            $table->foreign('current_state_id')->references('id')->on('states');
            $table->foreignId('current_city_id');
            $table->foreign('current_city_id')->references('id')->on('cities');
            $table->foreignId('home_town_country_id');
            $table->foreign('home_town_country_id')->references('id')->on('countries');
            $table->foreignId('home_town_state_id');
            $table->foreign('home_town_state_id')->references('id')->on('states');
            $table->foreignId('home_town_city_id');
            $table->foreign('home_town_city_id')->references('id')->on('cities');

            $table->string('role_id');
            $table->string('role_name');
            $table->string('user_key')->nullable();
            $table->string('first_name' , 30);
            $table->string('middle_name' , 30)->nullable();
            $table->string('last_name' , 30);
            $table->string('email')->unique();
            $table->timestamp('credentials_send_at')->nullable();
            $table->string('secondary_email')->nullable();
            $table->string('password')->nullable();
            $table->string('country_code_phone_number' , 10);
            $table->string('phone_number' , 15);
            $table->string('country_code_secondary_phone_number' , 10)->nullable();
            $table->string('secondary_phone_number', 15)->nullable();
            $table->enum('gender', ['Male', 'Female', 'Transgender', 'Prefer not to say', 'Other']);
            $table->date('dob');
            $table->string('current_address_1');
            $table->string('current_address_2')->nullable();
            $table->string('current_zip_code');
            $table->string('home_address_1');
            $table->string('home_address_2')->nullable();
            $table->string('home_zip_code' , 15);
            $table->string('profile_photo_url')->nullable();
            $table->boolean('is_first_login')->default(1);  // 1 => yes, 0 => no
            $table->boolean('is_password_reset')->default(0);  // 0 => no, 1 => yes
            $table->boolean('registration')->default(0);  // 0 => no (Registration not completed), 1 => yes (Registration is completed)
            $table->boolean('is_active')->default(1);  // 0 => no, 1 => yes
            $table->string('created_by' , 20);
            $table->string('updated_by' , 20)->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();

            $table->index(['practice_id', 'department_id', 'department_employee_type_id', 'role_id', 'gender']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
