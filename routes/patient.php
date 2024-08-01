<?php

use App\Http\Controllers\Insurance\InsuranceController;
use App\Http\Controllers\Patient\Appointment\AppointmentController;
use App\Http\Controllers\Patient\Auth\AuthController;
use App\Http\Controllers\Patient\Auth\AuthController as AuthAuthController;
use App\Http\Controllers\Patient\Register\RegisterPatientController;
use App\Http\Controllers\Patient\PatientHistory\PatientHistoryController;
use App\Http\Controllers\Practice\Patient\PatientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EPrescription\EPrescriptionController;
use App\Http\Controllers\EPrescription\VitalController;
use App\Http\Controllers\PatientPortal\PatientPortalController;
use App\Http\Controllers\gCalendarContoller;
use App\Http\Controllers\oCalendarController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
      Route::post('allergies', [PatientHistoryController::class, 'getAllAllergy']);
      Route::get('ethnicities', [PatientHistoryController::class, 'getAllEthnicity']);
      Route::get('races', [PatientHistoryController::class, 'getAllRaces']);
      Route::get('languages', [PatientHistoryController::class, 'getAllLanguages']);
      Route::get('routes', [PatientHistoryController::class, 'getAllRoutes']);
      Route::get('sites', [PatientHistoryController::class, 'getAllSite']);
      Route::post('manufactures', [PatientHistoryController::class, 'getVaccineManufacture']);
      Route::post('ndcs', [PatientHistoryController::class, 'getVaccineNationalDrugCode']);
      Route::get('countries', [PatientHistoryController::class, 'getAllCountry']);
      Route::get('states/{country_id}', [PatientHistoryController::class, 'getCountryState']);
      Route::get('cities/{state_id}', [PatientHistoryController::class, 'getStateCity']);
      Route::post('vaccines', [PatientHistoryController::class, 'allVaccine']);
      Route::post('surgeries', [PatientHistoryController::class, 'allSurgery']);
      Route::get('reactions', [PatientHistoryController::class, 'allReaction']);
      Route::post('medical-problems', [PatientHistoryController::class, 'allMedicalProblem']);
      Route::get('relationships', [PatientHistoryController::class, 'allRelationship']);



      Route::group(['prefix' => 'patient'], function () {

      //admin route
      Route::post('patient-register', [RegisterPatientController::class, 'registerPatient']);
      Route::post('edit-patient-basic-info', [RegisterPatientController::class, 'editPatientBasicInformation']);

      Route::post('check-patient', [RegisterPatientController::class, 'checkPatientExist']);
      Route::post('check-patient-login', [RegisterPatientController::class, 'checkPatientLogin']);
      Route::post('send-verification-code', [RegisterPatientController::class, 'sendMobileVerificationCode']);
      Route::post('verify-verification-code', [RegisterPatientController::class, 'verifyMobileVerificationCode']);
      Route::post('set-password', [RegisterPatientController::class, 'setPassword']);
      Route::post('practice-patient/{practice_id}', [PatientHistoryController::class, 'getPracticePatient']);
      Route::get('patient/{patient_id}', [PatientHistoryController::class, 'getPatient']);
      Route::post('verify-otp', [RegisterPatientController::class, 'verifyOtp']);
      Route::post('send-OTP', [RegisterPatientController::class, 'sendOTP']);
      // Insurance
      Route::post('add-insurance', [InsuranceController::class,  'addInsurance']);
      Route::get('insurance-list/{id}', [InsuranceController::class,  'insuranceList']);
      Route::get('insurance-company-list/{id}', [InsuranceController::class,  'InsuranceCompanyList']);

      Route::post('login', [AuthAuthController::class, 'login']);

      //apply middleware
      Route::middleware(['auth:patient-api','user_type'])->group(function () {


      Route::post('update-password', [RegisterPatientController::class, 'updatePassword']);

      //appointment
      Route::post('appointment-list', [AppointmentController::class, 'appointmentList']);
      Route::post('appointment-create', [AppointmentController::class, 'appointmentCreate']);
      Route::post('re-schedule', [AppointmentController::class, 'reSchedule']);
      Route::get('practice-list', [AppointmentController::class, 'practiceList']);
      Route::post('doctor-list', [AppointmentController::class, 'doctorList']);

      Route::get('appointment-list-monthly', [AppointmentController::class,  'appointmentListByMonth']);
      Route::get('appointment-list-pervious-monthly', [AppointmentController::class,  'appointmentListByMonthToPreviousDate']);
      Route::post('patient-appointment-list', [PatientController::class,  'patientAppointmentList']);

      // Appointment list by Patient
      Route::post('patient-appointment-details', [PatientController::class,  'patientAppointmentDetails']);
      Route::post('patient-appointment-list', [PatientController::class,  'patientAppointmentList']);


      Route::post('doctor-specializations-list', [AppointmentController::class, 'doctorSpecializationsList']);
      Route::post('specialization-list', [AppointmentController::class, 'specializationList']);
      Route::post('specialization-doctor', [AppointmentController::class, 'specializationsWithDoctor']);
      Route::post('doctor-slot/{doctor_id}', [AppointmentController::class, 'doctorSlot']);
      Route::get('medical-problem-list', [AppointmentController::class, 'medicalProblemList']);
      Route::post('appointment-date-list', [AppointmentController::class,  'getAppointmentByIdAndDate']);
      Route::post('appointment-date', [AppointmentController::class,  'getAppointmentList']);

      Route::post('set-reference-contact', [PatientHistoryController::class, 'setPatientReferenceContact']);
      Route::get('reference-contacts/{patient_id}', [PatientHistoryController::class, 'allReferenceContact']);
      Route::get('delete-reference-contact/{contact_id}', [PatientHistoryController::class, 'deletePatientReferenceContact']);

      Route::post('set-family-history', [PatientHistoryController::class, 'setPatientFamilyHistory']);
      Route::get('family-history/{patient_id}', [PatientHistoryController::class, 'allFamilyHistory']);
      Route::get('delete-family-history/{history_id}', [PatientHistoryController::class, 'deleteFamilyHistory']);

      Route::post('set-medical-problem', [PatientHistoryController::class, 'setPatientMedicalProblem']);
      Route::get('patient-medical-problem/{patient_id}', [PatientHistoryController::class, 'allPatientMedicalProblem']);
      Route::get('delete-patient-medical-problem/{problem_id}', [PatientHistoryController::class, 'deletePatientMedicalProblem']);

      Route::post('set-patient-surgery-history', [PatientHistoryController::class, 'setPatientSurgicalHistory']);
      Route::get('patient-surgery-history/{patient_id}', [PatientHistoryController::class, 'allPatientSurgicalHistory']);
      Route::get('delete-patient-surgery-history/{surgery_id}', [PatientHistoryController::class, 'deletePatientSurgicalHistory']);

      Route::post('set-patient-vaccine', [PatientHistoryController::class, 'setPatientVaccine']);
      Route::get('patient-vaccine/{patient_id}', [PatientHistoryController::class, 'allPatientVaccine']);
      Route::get('delete-patient-vaccine/{patient_vaccine_id}', [PatientHistoryController::class, 'deletePatientVaccine']);


      Route::post('set-patient-social-history', [PatientHistoryController::class, 'setPatientSocialHistory']);
      Route::get('patient-social-history/{patient_id}', [PatientHistoryController::class, 'getPatientSocialHistory']);
      Route::get('delete-patient-social-history/{social_history_id}', [PatientHistoryController::class, 'deletePatientSocialHistory']);

      Route::post('set-patient-allergy', [PatientHistoryController::class, 'setPatientAllergy']);
      Route::get('patient-allergy/{patient_id}', [PatientHistoryController::class, 'getPatientAllergy']);
      Route::get('delete-patient-allergy/{patient_allergy_id}', [PatientHistoryController::class, 'deletePatientAllergy']);

      Route::post('set-patient-privacy', [PatientHistoryController::class, 'setPatientPrivacy']);
      Route::get('patient-privacy/{patient_id}', [PatientHistoryController::class, 'getPatientPrivacy']);
      Route::get('delete-patient-privacy/{social_privacy_id}', [PatientHistoryController::class, 'deletePatientPrivacy']);

      Route::post('set-patient-contact', [PatientHistoryController::class, 'setPatientContact']);
      Route::get('patient-contact/{patient_id}', [PatientHistoryController::class, 'getPatientContact']);
      Route::get('delete-patient-contact/{patient_contact_id}', [PatientHistoryController::class, 'deletePatientContact']);

      Route::post('set-patient-demography', [PatientHistoryController::class, 'setPatientDemography']);
      Route::get('patient-information/{patient_id}', [PatientHistoryController::class, 'getPatientInformation']);
      Route::post('set-patient-employment', [PatientHistoryController::class, 'setPatientEmployment']);
      Route::get('patient-employment/{patient_id}', [PatientHistoryController::class, 'getPatientEmployment']);

      Route::post('patient-reference-contact', [PatientHistoryController::class, 'getPatientReferenceContact']);
      Route::post('set-patient-identification', [PatientHistoryController::class, 'setPatientIdentification']);

      Route::post('change-patient-phone-number', [RegisterPatientController::class, 'changePhoneNumber']);


      Route::get('user-email', [oCalendarController::class, 'getUserEmail']);

      // Notifications
      Route::post('notifications', [PatientController::class, 'allNotifications']);
      Route::post('notification-read', [PatientController::class, 'markNotificationAsRead']);
      Route::post('mark-all-notifications-as-read', [PatientController::class, 'markAllNotificationsAsRead']);

    });
});


Route::prefix('patientPortal')->group(function () {
     Route::middleware('auth:patient-api')->group(function () {

      Route::post('/patient-vitals', [PatientPortalController::class, 'getPatientVitals']);

      Route::get('/view/{eId}/', [PatientPortalController::class, 'viewPrescriptionByEPrescriptionId']);

      Route::get('/viewPatient', [PatientPortalController::class, 'viewEPrescriptionByPatientId']);

      Route::get('/generate-e-prescription-pdf/{appointment_id}/', [PatientPortalController::class, 'generateEPrescription']);

      Route::post('/change-appointment-status', [PatientPortalController::class, 'changeAppointmentStatus']);

     });
 });
