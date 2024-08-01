<?php

namespace Database\Seeders;

use App\Models\Doctor\Doctor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Doctor::truncate();

        $doctor = Doctor::create([
            'practice_id' => 1,
            'doctor_key'=> 'doctor-1',
            'suffix'=> 'Mr',
            'first_name' => 'Martin',
            'middle_name'=> 'luthor',
            'last_name' => 'jack',
            'primary_email'=> 'primary@gmail.com',
            'secondary_email'=> 'secondary@gmail.com',
            'password' => Hash::make('123456789'), // <---- password
            'gender' => 'Male',
            'country_code_primary_phone_number'=> '+92',
            'primary_phone_number'=> '12345678',
            'marital_status'=> 'Married',
            'dob' => '1986-01-12',
            'license_photo_url' => \Illuminate\Http\UploadedFile::fake()->create('license.pdf')->store('public/practice/1/doctor/1/documents'),
            'passport_photo_url' => \Illuminate\Http\UploadedFile::fake()->create('passport.pdf')->store('public/practice/1/doctor/1/documents'),
            'emirate_photo_url' => \Illuminate\Http\UploadedFile::fake()->create('emirateID.pdf')->store('public/practice/1/doctor/1/documents'),
            'is_active' => 1,
            'is_first_login' => 1,
            'is_password_reset' => 1,
            'account_registration' => 1,
            'kyc_status' => 'Accepted',
            'draft_status' => 0,
            'created_by' => 'practice-1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $doctor->doctorAddress()->create([
            'current_country_id' => 1,
            'current_state_id' => 1,
            'current_city_id' => 1,
            'home_town_country_id' => 2,
            'home_town_state_id' => 6,
            'home_town_city_id' => 5,
            'current_address_1' => '1-Berkshire Circle Tennessee',
            'current_address_2' => '1-Berkshire Circle Tennessee',
            'current_zip_code' => 60007,
            'home_town_address_1' => '2-Lawrance road',
            'home_town_address_2' => '2-Lawrance road',
            'home_town_zip_code' => 54000,
            'created_by' => 'practice-1',
        ]);

        $doctor->doctorLegalInformation()->create([
            'license_number' => '123454232',
            'emirate_id' => '875765765765765',
            'passport_number' => 'USA123454232',
            'created_by' => 'practice-1',
        ]);

        $doctor->doctorSpecializations()->create([
            'specialization_id' => 2,
            'created_by' => 'practice-1',
        ]);

        $doctor->doctorSpecializations()->create([
            'specialization_id' => 3,
            'created_by' => 'practice-1',
        ]);

        $doctor->doctorFees()->create([
            'practice_id' => $doctor->practice_id,
            'amount' => 450,
            'status' => 1,
            'created_by' => 'doctor-1',
        ]);

        $doctor->doctorOffDays()->create([
            'practice_id' => $doctor->practice_id,
            'date' => Carbon::now()->addDays(4)->format('Y-m-d')
        ]);

        $doctor->doctorOffDays()->create([
            'practice_id' => $doctor->practice_id,
            'date' => Carbon::now()->addDays(4)->format('Y-m-d')
        ]);

        $doctor->doctorOffDays()->create([
            'practice_id' => $doctor->practice_id,
            'date' => Carbon::now()->addDays(8)->format('Y-m-d')
        ]);

        $doctorSlot = $doctor->doctorSlots()->create([
            'practice_id' => $doctor->practice_id,
            'date_from' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'date_to' => Carbon::now()->addMonths(3)->format('Y-m-d'),
            'time_from' => '10:00 AM',
            'time_to' => '12:00 PM',
            'timezone' => 'Alaska time',
            'slot_time' => 20,
            'status' => 1,
            'created_by' => 'doctor-1',
        ]);

        $doctorSlot->doctorSlotDays()->create([
            'day' => 'Monday',
        ]);

        $doctorSlot = $doctor->doctorSlots()->create([
            'practice_id' => $doctor->practice_id,
            'date_from' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'date_to' => Carbon::now()->addMonths(3)->format('Y-m-d'),
            'time_from' => '10:00 AM',
            'time_to' => '12:00 PM',
            'timezone' => 'Alaska time',
            'slot_time' => 20,
            'status' => 1,
            'created_by' => 'doctor-1',
        ]);

        $doctorSlot->doctorSlotDays()->create([
            'day' => 'Tuesday',
        ]);

        $doctor->doctorPracticeRequests()->create([
            'practice_id' => $doctor->practice_id,
            'status' => 'Accepted',
            'count' => 1,
            'created_by' => $doctor->practice->practice_key,
        ]);

        $doctorPractice = $doctor->doctorPractices()->create([
            'practice_id' => $doctor->practice_id,
            'role_id' => 2,
            'role_name' => 'Doctor',
            'currently_active_in_practice_status' => 1,
            'created_by' => $doctor->practice->practice_key,
        ]);

        for ($i=49; $i<=88; $i++)
            {
                $permission = Permission::where('id', $i)->first();
                $doctorPractice->doctorPracticePermissions()->create([
                    'doctor_practice_id' => $doctorPractice->practice_id,
                    'permission_id' => $permission->id,
                    'permission_name' => $permission->name,
                ]);
            }
    }
}
