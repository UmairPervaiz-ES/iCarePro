<?php

namespace Tests\Feature;

use App\Models\Doctor\DoctorPractice;
use App\Models\Practice\Practice;
use App\Models\Practice\PracticeAddress;
use App\Models\Subscription\SubscriptionPermission;
use Database\Seeders\AllergySeeder;
use Database\Seeders\CitySeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\ManufactureSeeder;
use Database\Seeders\MedicalProblemSeeder;
use Database\Seeders\NationalDrugCodeSeeder;
use Database\Seeders\PatientRelationshipSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\ReactionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\RouteSeeder;
use Database\Seeders\SiteSeeder;
use Database\Seeders\SpecializationSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\SurgeryProcedureSeeder;
use Database\Seeders\VaccineSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PracticeDataTest extends TestCase
{
    /**
     * Practice Stats
     *
     * @return void
     */
    public function test_practice_stats()
    {
        $response =  $this->getJson('api/practice/stats', $this->headers['header']);
        $response->assertStatus(200);
    }
    /**
     * Practice Stats
     *
     * @return void
     */
    public function test_store_doctor()
    {
        $this->seed(CountrySeeder::class);
        $this->seed(StateSeeder::class);
        $this->seed(CitySeeder::class);
        $this->seed(SpecializationSeeder::class);

        // Set Doctor
        $doctorData = [
            'suffix'    => 'Mr',
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'primary_email' => $this->faker->email,
            'gender' => 'Male',
            'country_code_primary_phone_number' => '92',
            'primary_phone_number' => $this->faker->numerify('#########'),
            'dob' => date('y-m-d'),
            'current_country_id' => '167',
            'current_state_id' => '3171',
            'current_city_id' => '85358',
            'home_town_country_id' => '231',
            'home_town_state_id' => '3392',
            'home_town_city_id' => '47',
            'current_address_1' => 'ABC',
            'current_zip_code' => '432',
            'home_town_address_1' => 'DSA',
            'home_town_zip_code' => '321',
            'specialization_id' => ['2'],
        ];
        $response =  $this->postJson('api/practice/store-doctor', $doctorData, $this->headers['header']);
        $response->assertStatus(200);
        return $response['doctor'];
    }

    /**
     * Get Registration Requests
     *
     * @return void
     */
    public function test_get_registration_requests()
    {
        $response = $this->getJson('api/practice/list-of-registration-requests/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Get List Doctors
     *
     * @return void
     */
    public function test_practice_get_doctors()
    {
        $response = $this->postJson('api/practice/doctors', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Get Specific Doctor
     *
     * @return void
     */
    public function test_practice_get_specific_doctor()
    {
        $doctor = $this->test_store_doctor();
        DoctorPractice::create([
            'practice_id' => $this->headers['practice']['id'],
            'doctor_id' => $doctor['id'],
            'role_id' => '2',
            'role_name' => 'Doctor',
            'doctor_status_in_practice' => 'True',
            'currently_active_in_practice_status' => 'True',
            'created_by' => $doctor['created_by'],
        ]);

        $response = $this->getJson('api/practice/doctor/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Update Personal Information
     *
     * @return void
     */
    public function test_practice_update_personal_information()
    {
        $this->test_store_doctor();

        $personalInfo = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => 'Male',
            'doctor_id' => '1',
            'practice_id' => '1',
            'specializationIDs' => ['2'],
        ];
        $response = $this->postJson('api/practice/update-personal-information', $personalInfo, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Update Specialization
     *
     * @return void
     */
    public function test_practice_update_specialization()
    {
        $this->test_store_doctor();

        $specialization = [
            'doctor_id' => '1',
            'specialization_ids' => ['2'],
        ];
        $response = $this->postJson('api/practice/update-specialization', $specialization, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Update About me
     *
     * @return void
     */
    public function test_practice_update_about_me()
    {
        $this->test_store_doctor();

        $about = [
            'doctor_id' => '1',
            'about_me' => 'About me',
        ];
        $response = $this->postJson('api/practice/update-about-me', $about, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Update Contact Information
     *
     * @return void
     */
    public function test_practice_update_contact_information()
    {
        $this->test_store_doctor();

        $contact = [
            'country_code_primary_phone_number' => '92',
            'primary_phone_number' => $this->faker->numerify('#########'),
            'country_code_secondary_phone_number' => '32',
            'secondary_phone_number' => $this->faker->numerify('#########'),
            'secondary_email' => $this->faker->email,
        ];
        $response = $this->postJson('api/practice/update-contact-information', $contact, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Update Current Address
     *
     * @return void
     */
    public function test_practice_update_current_address()
    {
        $this->test_store_doctor();

        $contact = [
            'current_address_1' => 'DSA',
            'current_address_2' => 'asd',
            'current_state_id' => '3171',
            'current_country_id' => '167',
            'current_city_id' => '55358',
            'current_zip_code' => '321',
        ];
        $response = $this->postJson('api/practice/update-current-address', $contact, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Upload Document
     *
     * @return void
     */
    public function test_practice_upload_document()
    {
        $this->test_store_doctor();
        $file = UploadedFile::fake()->create('license.pdf')->store('public/practice/1/doctor/1/documents');
        $filePaths = [
            'file_paths' => [$file],
            'doctor_id' => 1,
        ];
        $response = $this->postJson('api/practice/upload-document', $filePaths, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Delete Document
     *
     * @return void
     */
    public function test_practice_delete_document()
    {
        $this->test_practice_upload_document();
        $file = [
            'id' => 1,
        ];
        $response = $this->postJson('api/practice/delete-document', $file, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Doctor Fee List
     *
     * @return void
     */
    public function test_practice_doctor_fee_list()
    {
        $response = $this->postJson('api/practice/doctor-fee-list/1', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Add Doctor Fee
     *
     * @return void
     */
    public function test_practice_add_doctor_fee()
    {
        $this->test_store_doctor();
        $fees = [
            'amount' => '234',
        ];
        $response = $this->postJson('api/practice/add-doctor-fee', $fees, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Update Doctor Fee Status
     *
     * @return void
     */
    public function test_practice_update_doctor_fee_status()
    {
        $this->test_practice_add_doctor_fee();

        $response = $this->postJson('api/practice/update-doctor-fee-status/1', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Doctor List of Slots
     *
     * @return void
     */
    public function test_practice_list_slots()
    {
        $this->test_store_doctor();

        $response = $this->postJson('api/practice/list-of-slots/1', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Doctor Add Slot
     *
     * @return void
     */
    public function test_practice_add_slot()
    {
        $this->test_store_doctor();
        $slot = [
            'date_from' => '2022-11-23',
            'date_to' => '2023-12-21',
            'time_from' => '10:00 AM',
            'time_to' => '11:00 AM',
            'slot_time' => '20',
            'days' => ['Monday'],
        ];

        $response = $this->postJson('api/practice/add-slot', $slot, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Publish Slot
     *
     * @return void
     */
    public function test_practice_publish_slot()
    {
        $this->test_practice_add_slot();
        $slot = [
            'ids' => ['1'],
        ];

        $response = $this->postJson('api/practice/publish-slot', $slot, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Deactivate Slot
     *
     * @return void
     */
    public function test_practice_deactivate_slot()
    {
        $this->test_practice_add_slot();
        $slot = [
            'id' => '1',
        ];

        $response = $this->postJson('api/practice/deactivate-slot', $slot, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * List Off Dates
     *
     * @return void
     */
    public function test_practice_list_off_dates()
    {
        $response = $this->getJson('api/practice/list-of-off-dates/1/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Add Off Dates
     *
     * @return void
     */
    public function test_practice_add_off_dates()
    {
        $this->test_store_doctor();
        $dates = [
            'dates' => ['2022-11-25'],
        ];
        $response = $this->postJson('api/practice/add-off-dates', $dates, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Delete Off Dates
     *
     * @return void
     */
    public function test_practice_delete_off_dates()
    {
        $this->test_store_doctor();
        $offDates = [
            'ids' => ['1'],
        ];
        $response = $this->postJson('api/practice/delete-off-dates', $offDates, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Doctors List
     *
     * @return void
     */
    public function test_practice_doctors_list()
    {
        $response = $this->postJson('api/practice/doctor', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Doctor Specialization List
     *
     * @return void
     */
    public function test_practice_doctor_specialization_list()
    {
        $response = $this->postJson('api/practice/doctor-specializations-list', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Doctor Slot
     *
     * @return void
     */
    public function test_practice_doctor_slot()
    {
        $this->test_store_doctor();
        $response = $this->postJson('api/practice/doctor-slot/1', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Specialization List
     *
     * @return void
     */
    public function test_practice_specialization_list()
    {
        $response = $this->postJson('api/practice/specialization-list', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Specialization Doctor
     *
     * @return void
     */
    public function test_practice_specialization_doctor()
    {
        $response = $this->postJson('api/practice/specialization-doctor', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Test Patient Register
     *
     * @return void
     */
    public function test_patient_register()
    {
        $patientData = [
            'email'    => 'sample@test.com',
            'password' => 'sample123',
            'patient_key' => 'Patient-1',
            'country_code' => '92',
            'phone_number' => $this->faker->numerify('#########'),
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->lastName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'gender' => 'Male',
            'dob' => date('y-m-d'),
            'password' => ('123456'),
            'thumbnail_photo_url' => '',
            'profile_photo_url' => '',
            'created_by' => '',
            'is_phone_number_verified' =>  ''
        ];

        $response = $this->postJson('api/patient/patient-register', $patientData);
        $response->assertStatus(201);
        return $response;
    }

    /**
     * Practice Create Appointment
     *
     * @return void
     */
    public function test_practice_create_appointment()
    {
        $patient = $this->test_patient_register();
        $this->test_practice_add_slot();

        $appointment = [
            'practice_id' => $this->headers['practice']['id'],
            'doctor_id' => '1',
            'patient_id' => $patient['data']['id'],
            'start_time' => '10:00',
            'end_time' => '10:30',
            'date' => '2022-11-07',
            'doctor_slot_id' => '1',
            'instructions' => '1instructions',
            'medical_problem_id' => ['1'],
        ];
        $response = $this->postJson('api/practice/create-appointment', $appointment, $this->headers['header']);
        $response->assertStatus(201);
        return ['patient'=> $patient, 'appointment'=> $response];
    }

    /**
     * Practice Appointment List
     *
     * @return void
     */
    public function test_practice_appointment_list()
    {
        $response = $this->postJson('api/practice/appointment-list', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Reschedule
     *
     * @return void
     */
    public function test_practice_re_schedule()
    {
        $appointment = $this->test_practice_create_appointment();
        $reSchedule = [
            'practice_id' => $this->headers['practice']['id'],
            'doctor_id' => '1',
            'id' => $appointment['appointment']['data']['id'],
            'patient_id' => $appointment['patient']['data']['id'],
            'start_time' => '10:00',
            'end_time' => '10:30',
            'date' => '2022-11-08',
            'doctor_slot_id' => '1',
            'instructions' => '1instructions',
            'medical_problem_id' => ['1'],
        ];
        $response = $this->postJson('api/practice/re-schedule', $reSchedule, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Patient List
     *
     * @return void
     */
    public function test_practice_patient_list()
    {
        $response = $this->postJson('api/practice/patient-list', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Medical Problem List
     *
     * @return void
     */
    public function test_practice_medical_problem_list()
    {
        $response = $this->getJson('api/practice/medical-problem-list', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Appointment Date
     *
     * @return void
     */
    public function test_practice_appointment_date()
    {
        $date = [
            'doctor_id' => '1',
            'date' => '2022-11-08',
        ];
        $response = $this->postJson('api/practice/appointment-date', $date, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Appointment Chart
     *
     * @return void
     */
    public function test_practice_appointment_chart()
    {
        $response = $this->getJson('api/practice/appointment-chart', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Appointment Spline Graph
     *
     * @return void
     */
    public function test_practice_appointment_spline_graph()
    {
        $response = $this->getJson('api/practice/appointment-spline-graph', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Appointment Count
     *
     * @return void
     */
    public function test_practice_appointments_count()
    {
        $response = $this->postJson('api/practice/appointments-count', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Upcoming Appointment List
     *
     * @return void
     */
    public function test_practice_upcoming_appointments_list()
    {
        $response = $this->postJson('api/practice/upcoming-appointments-list', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Doctor Appointment List
     *
     * @return void
     */
    public function test_practice_doctor_appointment_list()
    {
        $response = $this->postJson('api/practice/doctor-appointment-list', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Patient Appointment Detail
     *
     * @return void
     */
    public function test_practice_patient_appointment_detail()
    {
        $this->test_practice_create_appointment();

        $appointment = [
            'id' => '1',
        ];
        $response = $this->postJson('api/practice/patient-appointment-details', $appointment, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Patient Appointment List
     *
     * @return void
     */
    public function test_practice_patient_appointment_list()
    {
        $response = $this->postJson('api/practice/patient-appointment-list', [], $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Doctor Pending List
     *
     * @return void
     */
    public function test_practice_doctor_pending_list()
    {
        $this->test_store_doctor();

        DoctorPractice::create([
            'doctor_id' => 1,
            'practice_id' => 1,
            'role_id' => 2,
            'role_name' => 'Doctor',
            'doctor_status_in_practice' => 'TRUE',
            'currently_active_in_practice_status' => 'TRUE',
            'created_by' => 'practice-1',
        ]);

        $response = $this->getJson('api/practice/doctor-pending-list', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Doctor Pending Response
     *
     * @return void
     */
    public function test_practice_doctor_pending_response()
    {
        $this->test_store_doctor();

        DoctorPractice::create([
            'doctor_id' => 1,
            'practice_id' => 1,
            'role_id' => 2,
            'role_name' => 'Doctor',
            'doctor_status_in_practice' => 'TRUE',
            'currently_active_in_practice_status' => 'TRUE',
            'created_by' => 'practice-1',
        ]);
        $pending = [
            'kyc_status' => 'Accepted',
        ];
        $response = $this->postJson('api/practice/doctor-pending-response/1', $pending, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set Consent Form Type
     *
     * @return void
     */
    public function test_practice_set_consent_form_type()
    {
        $consentForm = [
            'category' => 'DOCTOR',
            'sub_category' => 'REGISTRATION',
            'type' => 'GDPR',
            'is_required' => '1',
        ];
        $response = $this->postJson('api/practice/set-consent-form-type', $consentForm, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Set Consent Form
     *
     * @return void
     */
    public function test_practice_set_consent_form()
    {
        $this->test_practice_set_consent_form_type();
        $consentForm = [
            'consent_form_type_id' => '1',
            'version' => '1.1',
            'content' => 'REGISTRATION',
            'content_status' => 'SAVE',
            'publish_status' => 'ACTIVE',
            'published_at' => Date('Y-m-d'),
        ];
        $response = $this->postJson('api/practice/set-consent-form', $consentForm, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Consent Forms
     *
     * @return void
     */
    public function test_practice_consent_forms()
    {
        $response = $this->getJson('api/practice/consent-forms', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Publish Consent Forms
     *
     * @return void
     */
    public function test_practice_publish_consent_forms()
    {
        $response = $this->getJson('api/practice/publish-consent-forms', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Add Consent Log
     *
     * @return void
     */
    public function test_practice_add_consent_log()
    {
        $this->test_practice_set_consent_form();
        $consentLog['request'] = [
            'consent_form_type_id' => '1',
            'consent_form_id' => '1',
            'consent_status' => 'AGREE',
            'category' => 'DOCTOR',
            'category_id' => '1',
        ];
        $response = $this->postJson('api/practice/add-consent-log', $consentLog, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Register Doctor Consent Form
     *
     * @return void
     */
    public function test_practice_register_doctor_consent_form()
    {
        $response = $this->getJson('api/practice/register-doctor-consent-forms', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Register Doctor Publish Consent Forms
     *
     * @return void
     */
    public function test_practice_register_doctor_publish_consent_forms()
    {
        $response = $this->getJson('api/practice/register-doctor-publish-consent-forms', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Register Patient Consent Forms
     *
     * @return void
     */
    public function test_practice_register_patient_consent_forms()
    {
        $response = $this->getJson('api/practice/register-patient-consent-forms', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Register Patient Publish Consent Forms
     *
     * @return void
     */
    public function test_practice_register_patient_publish_consent_forms()
    {
        $response = $this->getJson('api/practice/register-patient-publish-consent-forms', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Departments
     *
     * @return void
     */
    public function test_practice_departments()
    {
        $response = $this->getJson('api/practice/departments', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Add Department
     *
     * @return void
     */
    public function test_practice_add_department()
    {
        $department = [
            'name' => $this->faker->name,
        ];
        $response = $this->postJson('api/practice/add-department', $department, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Edit Department
     *
     * @return void
     */
    public function test_practice_edit_department()
    {
        $this->test_practice_add_department();
        $department = [
            'name' => 'Finance',
            'department_id' => '1',
        ];
        $response = $this->postJson('api/practice/edit-department', $department, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Add Department Employee Type
     *
     * @return void
     */
    public function test_practice_add_department_employee_type()
    {
        $department = $this->test_practice_add_department();
        $department = [
            'name' => ['Nurse', 'Doctor'],
            'department_id' => '1',
            
        ];
        $response = $this->postJson('api/practice/add-department-employee-type', $department, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Add Department Employee Type
     *
     * @return void
     */
    public function test_practice_department_employee_type_update()
    {
        $this->test_practice_add_department_employee_type();

        $department = [
            'name' => 'Nurse',
            'status' => '1',
            
        ];
        $response = $this->postJson('api/practice/department-employee-type-status-update/1', $department, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Add Role
     *
     * @return void
     */
    public function test_practice_add_role()
    {
        $role = [
            'name' => 'Practice-1@admin',
            'guard_name' => 'api',
        ];
        $response = $this->postJson('api/practice/add-role', $role, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Assign Permission to Role
     *
     * @return void
     */
    public function test_practice_assign_permission_role()
    {
        $this->test_practice_add_role();

        $role = [
            'role_id' => '1',
            'role_name' => 'Patient',
            'permission_ids' => [1],
        ];
        $response = $this->postJson('api/practice/assign-permissions-to-role', $role, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Roles
     *
     * @return void
     */
    public function test_practice_get_roles()
    {
        $this->test_practice_add_role();

        $response = $this->getJson('api/practice/roles', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Permissions
     *
     * @return void
     */
    public function test_practice_get_permissions()
    {
        // Assign Permission
        $this->assignPermission($this->headers['practice']);
        $response = $this->getJson('api/practice/permissions', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Add Staff
     *
     * @return void
     */
    public function test_practice_add_staff()
    {
        $this->test_practice_add_department();
        $this->test_practice_add_role();
        $this->test_practice_add_department_employee_type();
        $this->seed(CountrySeeder::class);
        $this->seed(StateSeeder::class);
        $this->seed(CitySeeder::class);
        $staff = [
            'role_id' => '1',
            'department_id' => '1',
            'department_employee_type_id' => '1',
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'country_code_phone_number' => '32',
            'phone_number' => $this->faker->numerify('#########'),
            'gender' => 'Male',
            'dob' => date('y-m-d'),
            'home_address_1' => 'ADDRESS 1',
            'home_town_country_id' => '167',
            'home_town_state_id' => '3171',
            'home_town_city_id' => '85358',
            'current_zip_code' => '4321',
            'current_address_1' => 'HY HELLO',
            'current_country_id' => '167',
            'current_state_id' => '3171',
            'current_city_id' => '85358',
            'home_zip_code' => '3171',
        ];
        $response = $this->postJson('api/practice/add-staff', $staff, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Get Staff
     *
     * @return void
     */
    public function test_practice_get_staff()
    {
        $this->test_practice_add_staff();
        $staff = [
           'user_id' => 1
        ];
        $response = $this->postJson('api/practice/staff', $staff, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Staffs
     *
     * @return void
     */
    public function test_practice_get_staffs()
    {
        $this->test_practice_add_staff();
        $staff = [
           'pagination' => 1
        ];
        $response = $this->postJson('api/practice/staffs', $staff, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Staff Status Update
     *
     * @return void
     */
    public function test_practice_staff_status_update()
    {
        $this->test_practice_add_staff();
        $staff = [
           'user_id' => 1,
           'status' => 'Active',
        ];
        $response = $this->postJson('api/practice/staff-status-update', $staff, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Patient Register
     *
     * @return void
     */
    public function test_practice_patient_register()
    {
        $patient = [
            'patient_key' => 'patient-1',
            'country_code' => '92',
            'phone_number' => $this->faker->numerify('#########'),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'gender' => 'Male',
            'dob' => date('y-m-d'),
            'password' => '123456',
            'practice_id' => '1',
        ];
        $response = $this->postJson('api/practice/patient-register', $patient, $this->headers['header']);
        $response->assertStatus(201);
        return $patient;
    }

    /**
     * Practice Check Patient
     *
     * @return void
     */
    public function test_practice_check_patient()
    {
        $patient = [
            'phone_number' => $this->faker->numerify('#########'),
        ];
        $response = $this->postJson('api/practice/check-patient', $patient, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Check Patient Login
     *
     * @return void
     */
    public function test_practice_check_patient_login()
    {
        $patient = [
            'phone_number' => $this->faker->numerify('#########'),
        ];
        $response = $this->postJson('api/practice/check-patient-login', $patient, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Patient
     *
     * @return void
     */
    public function test_practice_get_patient()
    {
        $patient = [
            'pagination' => 1,
        ];
        $response = $this->postJson('api/practice/practice-patient/1', $patient, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Specific Patient
     *
     * @return void
     */
    public function test_practice_get_specific_patient()
    {
        $response = $this->getJson('api/practice/patient/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Edit Patient Basic Info
     *
     * @return void
     */
    public function test_practice_edit_patient_basic_info()
    {
        $registerPatient = $this->test_practice_patient_register();
        $patient = [
            'phone_number' => $registerPatient['phone_number'],
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'gender' => 'Male',
            'dob' => date('y-m-d'),
        ];
        $response = $this->postJson('api/practice/edit-patient-basic-info', $patient, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set Reference Contact
     *
     * @return void
     */
    public function test_practice_set_reference_contact()
    {
        $contact = [
            'patient_id' => 1,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'contact_reference' => 'guarantor',
            'patient_relationship' => 'Spouse',
        ];
        $response = $this->postJson('api/practice/set-reference-contact', $contact, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Get Reference Contact
     *
     * @return void
     */
    public function test_practice_get_reference_contact()
    {
        $response = $this->getJson('api/practice/reference-contacts/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Delete Reference Contact
     *
     * @return void
     */
    public function test_practice_delete_reference_contact()
    {
        $this->test_practice_set_reference_contact();
        $response = $this->getJson('api/practice/delete-reference-contact/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set Patient Privacy
     *
     * @return void
     */
    public function test_practice_set_patient_privacy()
    {
        $privacy = [
            'patient_privacy_id' => 1,
            'patient_id' => 1,
        ];
        $response = $this->postJson('api/practice/set-patient-privacy', $privacy, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Get Patient Privacy
     *
     * @return void
     */
    public function test_practice_get_patient_privacy()
    {
        $response = $this->getJson('api/practice/patient-privacy/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Delete Patient Privacy
     *
     * @return void
     */
    public function test_practice_delete_patient_privacy()
    {
        $this->test_practice_set_patient_privacy();
        $response = $this->getJson('api/practice/delete-patient-privacy/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set Patient Contact
     *
     * @return void
     */
    public function test_practice_set_patient_contact()
    {
        $contact = [
            'patient_privacy_id' => 1,
            'patient_id' => 1,
        ];
        $response = $this->postJson('api/practice/set-patient-contact', $contact, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Get Patient Contact
     *
     * @return void
     */
    public function test_practice_get_patient_contact()
    {
        $response = $this->getJson('api/practice/patient-contact/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Delete Patient Contact
     *
     * @return void
     */
    public function test_practice_delete_patient_contact()
    {
        $this->test_practice_set_patient_contact();
        $response = $this->getJson('api/practice/delete-patient-contact/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set Patient Demography
     *
     * @return void
     */
    public function test_practice_set_patient_demography()
    {
        $demography = [
            'patient_privacy_id' => 1,
            'patient_id' => 1,
        ];
        $response = $this->postJson('api/practice/set-patient-demography', $demography, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Get Patient Information
     *
     * @return void
     */
    public function test_practice_get_patient_information()
    {
        $response = $this->getJson('api/practice/patient-information/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set Patient Employment
     *
     * @return void
     */
    public function test_practice_set_patient_employment()
    {
        $employment = [
            'patient_id' => 1,
            'occupation' => 'HR',
            'employer_name' => 'name',
        ];
        $response = $this->postJson('api/practice/set-patient-employment', $employment, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Get Patient Employment
     *
     * @return void
     */
    public function test_practice_get_patient_employment()
    {
        $response = $this->getJson('api/practice/patient-employment/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Patient Reference Contact
     *
     * @return void
     */
    public function test_practice_get_patient_reference_contact()
    {
        $reference = [
            'pagination' => 1,
            'patient_id' => 1,
            'contact_reference' => 'guarantor',
        ];
        $response = $this->postJson('api/practice/patient-reference-contact', $reference, $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set Patient Medical Problem
     *
     * @return void
     */
    public function test_practice_set_patient_medical_problem()
    {
        $this->seed(MedicalProblemSeeder::class);
        $medicalProblem = [
            'patient_id' => '1',
            'medical_problem_id' => '1',
            'status' => 'Active',
            'removal_reason' => 'None',
            'type' => 'Chronic',
            'onset_date' => date('y-m-d'),
            'last_occurrence' => date('y-m-d'),
            'note' => 'Note',
        ];
        $response = $this->postJson('api/practice/set-medical-problem', $medicalProblem, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Set Family History
     *
     * @return void
     */
    public function test_practice_set_family_history()
    {
        $this->seed(PatientRelationshipSeeder::class);

        $this->test_practice_set_patient_medical_problem();
        $family = [
            'medical_problem_id' => 1,
            'patient_id' => 1,
            'patient_family_history' => [
                0 =>[
                'patient_relationship_id' => 1,
                'onset_age' => '26',
                'died' => 'No',
                'note' => 'NOTE',
                'id' => '1',
                ]
            ],
        ];
        $response = $this->postJson('api/practice/set-family-history', $family, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Get Patient Family History
     *
     * @return void
     */
    public function test_practice_get_family_history()
    {
        $response = $this->getJson('api/practice/family-history/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Patient Family History
     *
     * @return void
     */
    public function test_practice_delete_family_history()
    {
        $this->test_practice_set_family_history();
        $response = $this->getJson('api/practice/delete-family-history/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Medical Problem
     *
     * @return void
     */
    public function test_practice_get_medical_problem()
    {
        $response = $this->getJson('api/practice/patient-medical-problem/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Delete Medical Problem
     *
     * @return void
     */
    public function test_practice_delete_medical_problem()
    {
        $this->test_practice_set_patient_medical_problem();
        $response = $this->getJson('api/practice/delete-patient-medical-problem/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set Patient Surgery History
     *
     * @return void
     */
    public function test_practice_set_patient_surgery_history()
    {
        $this->seed(SurgeryProcedureSeeder::class);

        $surgery = [
            'patient_id' => '1',
            'surgery_procedure_id' => '1',
            'date' => date('y-m-d'),
            'note' => 'Note',
        ];
        $response = $this->postJson('api/practice/set-patient-surgery-history', $surgery, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Get Patient Surgery History
     *
     * @return void
     */
    public function test_practice_patient_surgery_history()
    {
        $response = $this->getJson('api/practice/patient-surgery-history/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Delete Patient Surgery History
     *
     * @return void
     */
    public function test_practice_delete_patient_surgery_history()
    {
        $this->test_practice_set_patient_surgery_history();
        $response = $this->getJson('api/practice/delete-patient-surgery-history/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Vaccines
     *
     * @return void
     */
    public function test_practice_vaccines()
    {
        $response = $this->getJson('api/practice/vaccines', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Surgeries
     *
     * @return void
     */
    public function test_practice_surgeries()
    {
        $response = $this->getJson('api/practice/surgeries', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set Vaccine
     *
     * @return void
     */
    public function test_practice_set_patient_vaccine()
    {
        $this->seed(VaccineSeeder::class);
        $this->seed(RouteSeeder::class);
        $this->seed(NationalDrugCodeSeeder::class);
        $this->seed(SiteSeeder::class);
        $this->seed(ManufactureSeeder::class);

        $surgery = [
            'patient_id' => '1',
            'vaccine_id' => '1',
            'route_id' => '1',
            'national_drug_code_id' => '1',
            'site_id' => '1',
            'manufacture_id' => '1',
            'administer_date' => date('y-m-d'),
            'administer_by' => 'administer_by',
            'amount' => '234',
            'unit' => 'mcg',
            'lot_number' => '32',
            'expiry_date' => date('y-m-d'),
            'vaccine_given_date' => date('y-m-d'),
            'date_on_vaccine' => date('y-m-d'),
        ];
        $response = $this->postJson('api/practice/set-patient-vaccine', $surgery, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Patient Vaccine
     *
     * @return void
     */
    public function test_practice_patient_vaccine()
    {
        $response = $this->getJson('api/practice/patient-vaccine/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Delete Patient Vaccine
     *
     * @return void
     */
    public function test_practice_delete_patient_vaccine()
    {
        $this->test_practice_set_patient_vaccine();
        $response = $this->getJson('api/practice/delete-patient-vaccine/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Medical Problems
     *
     * @return void
     */
    public function test_practice_medical_problems()
    {
        $response = $this->getJson('api/practice/medical-problems', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Relationships
     *
     * @return void
     */
    public function test_practice_relationships()
    {
        $response = $this->getJson('api/practice/relationships', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set Social History
     *
     * @return void
     */
    public function test_practice_set_patient_social_history()
    {
        $surgery = [
            'patient_id' => '1',
            'gender_identity' => 'Identifies as Male',
            'sex_at_birth' => 'Male',
            'pronoun' => 'he/him',
            'first_name' => $this->faker->firstName,
            'sexual_orientation' => 'Lesbian, gay or homosexual',
        ];
        $response = $this->postJson('api/practice/set-patient-social-history', $surgery, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Get Patient Social History
     *
     * @return void
     */
    public function test_practice_get_patient_social_history()
    {
        $response = $this->getJson('api/practice/patient-social-history/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Delete Patient Social History
     *
     * @return void
     */
    public function test_practice_delete_patient_social_history()
    {
        $this->test_practice_set_patient_social_history();
        $response = $this->getJson('api/practice/delete-patient-social-history/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set Patient Allergy
     *
     * @return void
     */
    public function test_practice_set_patient_allergy()
    {
        $this->seed(AllergySeeder::class);
        $this->seed(ReactionSeeder::class);

        $surgery = [
            'patient_allergy_id' => '1',
            'patient_id' => '1',
            'allergy_id' => '1',
            'criticality' => 'High',
            'onset_date' => date('y-m-d'),
            'note' => 'Note',
            'patient_allergy_reaction' => [
                0 => [
                    'reaction_id' => 1,
                    'reaction_severity' => 'Mild',
                    'id' => 1,
                ]
            ],
        ];
        $response = $this->postJson('api/practice/set-patient-allergy', $surgery, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Get Patient Allergy
     *
     * @return void
     */
    public function test_practice_get_patient_allergy()
    {
        $response = $this->getJson('api/practice/patient-allergy/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Delete Patient Allergy
     *
     * @return void
     */
    public function test_practice_delete_patient_allergy()
    {
        $this->test_practice_set_patient_allergy();
        $response = $this->getJson('api/practice/delete-patient-allergy/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set Patient Identification
     *
     * @return void
     */
    public function test_practice_set_patient_identification()
    {
        $identification = [
            'patient_id' => '1',
            'legal_first_name' => $this->faker->firstName,
            'legal_last_name' => $this->faker->lastName,
            'legal_middle_name' => $this->faker->lastName,
            'suffix' => 'Miss',
            'legal_sex' => 'Male',
            'previous_name' => $this->faker->firstName,
            'dob' => date('y-m-d'),
            'emirates_id' => '234-4323456775-1',
            'mother_name' => $this->faker->firstName,
        ];
        $response = $this->postJson('api/practice/set-patient-identification', $identification, $this->headers['header']);
      
      
        $response->assertStatus(201);
    }

    /**
     * Practice Get Allergies
     *
     * @return void
     */
    public function test_practice_get_allergies()
    {
        $response = $this->getJson('api/practice/allergies', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Ethnicities
     *
     * @return void
     */
    public function test_practice_get_ethnicities()
    {
        $response = $this->getJson('api/practice/ethnicities', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Races
     *
     * @return void
     */
    public function test_practice_get_races()
    {
        $response = $this->getJson('api/practice/races', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Languages
     *
     * @return void
     */
    public function test_practice_get_languages()
    {
        $response = $this->getJson('api/practice/languages', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Routes
     *
     * @return void
     */
    public function test_practice_get_routes()
    {
        $response = $this->getJson('api/practice/routes', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Sites
     *
     * @return void
     */
    public function test_practice_get_sites()
    {
        $response = $this->getJson('api/practice/sites', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Vaccine Manufacture
     *
     * @return void
     */
    public function test_practice_get_vaccine_manufacture()
    {
        $response = $this->getJson('api/practice/vaccine-manufacture/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Get Vaccine ndc
     *
     * @return void
     */
    public function test_practice_get_vaccine_ndc()
    {
        $response = $this->getJson('api/practice/vaccine-ndc/1', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set Procedures
     *
     * @return void
     */
    public function test_practice_set_procedures()
    {
        $subscription = [
            'name' => $this->faker->firstName,
            'price' => '321',
            'description' =>  'Description',
        ];
        $response = $this->postJson('api/practice/set-practice-procedures', $subscription, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Remove Procedures
     *
     * @return void
     */
    public function test_practice_remove_procedures()
    {
        $this->test_practice_set_procedures();
        $response = $this->getJson('api/practice/remove-practice-procedures/1', $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Set Lab Tests
     *
     * @return void
     */
    public function test_practice_set_lab_tests()
    {
        $lab = [
            'name' => $this->faker->firstName,
            'price' => '321',
            'description' =>  'Description',
        ];
        $response = $this->postJson('api/practice/set-practice-lab-tests', $lab, $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice Remove Procedures
     *
     * @return void
     */
    public function test_practice_remove_lab_test()
    {
        $this->test_practice_set_lab_tests();
        $response = $this->getJson('api/practice/remove-practice-lab-tests/1', $this->headers['header']);
        $response->assertStatus(201);
    }

    /**
     * Practice EPrescription Template Data
     *
     * @return void
     */
    public function test_practice_ePrescription_template_data()
    {
        $response = $this->getJson('api/practice/ePrescription/template-data', $this->headers['header']);
        $response->assertStatus(200);
    }

    /**
     * Practice Set EPrescription Template Data
     *
     * @return void
     */
    public function test_practice_set_ePrescription_template_data()
    {
        $template = [
            'phone' => $this->faker->numerify('#########'),
            'country_code' => '92',
            'email' => $this->faker->email,
            'address' => 'Address',
            'disclaimer' => 'Disclaimer',
            'color_scheme' => 'Color',
        ];
        $response = $this->postJson('api/practice/ePrescription/set-template-data', $template, $this->headers['header']);
        $response->assertStatus(201);
    }
}
