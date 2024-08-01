<?php

use App\Http\Controllers\ConsentForm\ConsentFormController;
use App\Http\Controllers\Doctor\Appointment\AppointmentController;
use App\Http\Controllers\Doctor\Auth\AuthController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Insurance\InsuranceController;
use App\Http\Controllers\Patient\Appointment\AppointmentController as AppointmentAppointmentController;
use App\Http\Controllers\Patient\PatientHistory\PatientHistoryController;
use App\Http\Controllers\Patient\Register\RegisterPatientController;
use App\Http\Controllers\Practice\Doctor\DoctorDraftController;
use App\Http\Controllers\Practice\Patient\PatientController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Doctor API Routes
|--------------------------------------------------------------------------
|
*/

Route::get('practices', [AppointmentAppointmentController::class, 'practiceList']);
Route::prefix('doctor')->group(function () {

    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth:doctor-api','user_type'])->group(function () {

        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('switch-practice', [AuthController::class, 'switchPractice']);

        // Routes used by DOCTOR for updating and saving his details.
        Route::get('doctor-registration-details', [DoctorDraftController::class, 'getDoctorDetails']);  // Get request for doctor inorder to show him his added details as a draft
        Route::post('update-doctor-draft', [DoctorDraftController::class, 'updateDoctorDraftByDoctor'])->name('update-doctor-draft-by-doctor');
        Route::post('store-doctor-draft-by-doctor', [DoctorDraftController::class, 'storeDoctorDraftByDoctor'])->name('storeDoctorDraftByDoctor');

        // Practice requests for doctor
        Route::get('list-of-registration-requests/{pagination}', [DoctorController::class, 'listOfDoctorRegistrationRequests']);
        Route::post('update-registration-request-status', [DoctorController::class, 'updateDoctorRegistrationRequestStatus']);

        //appointment
        Route::post('appointment-list', [AppointmentController::class, 'appointmentList'])->name("doctor/appointment-list");
        Route::get('doctor-slot', [AppointmentController::class, 'doctorSlot']);
        Route::post('create-appointment', [AppointmentController::class, 'createAppointment']);
        Route::post('re-schedule', [AppointmentController::class, 'reSchedule']);
        Route::post('patient-list', [AppointmentController::class, 'patientList']);
        Route::get('medical-problem-list', [AppointmentController::class, 'medicalProblemList']);
        Route::post('appointment-date', [AppointmentController::class,  'getAppointmentByIdAndDate']);
        Route::post('appointments-count', [AppointmentController::class, 'appointmentListMonthlyCount']);
        Route::get('appointment-list-monthly', [AppointmentController::class,  'appointmentListByMonth']);
        Route::get('appointment-list-previous-monthly', [AppointmentController::class,  'appointmentListByMonthToPreviousDate']);

        // Calendar appointments view for doctor
        Route::post('dated-appointments', [DoctorController::class, 'calendarAppointmentsViewDates']);

        // Appointment list by Patient
        Route::post('appointment-details', [PatientController::class,  'patientAppointmentDetails']);

        // insurance
        Route::post('add-insurance', [InsuranceController::class,  'addInsurance']);
        Route::get('insurance-list', [InsuranceController::class,  'InsuranceList']);

        // Update additional documents
        Route::post('delete-document', [DoctorController::class, 'deleteDocument']);
        Route::post('upload-document', [DoctorController::class, 'uploadDocument']);

        // Update Routes
        Route::post('update-personal-information', [DoctorController::class, 'updatePersonalInformation']);

        // Update about me
        Route::post('update-about-me', [DoctorController::class, 'updateAboutMe']);

        // Update Contact information
        Route::post('update-contact-information', [DoctorController::class, 'updateContactInformation']);

        // Update current address
        Route::post('update-current-address', [DoctorController::class, 'updateCurrentAddress']);

        // Update Specialization
        Route::post('update-specialization', [DoctorController::class, 'updateSpecialization']);

        // Primary Email Update routes
        Route::post('request-otp-to-update-doctor-primary-email', [DoctorController::class, 'requestOtpToUpdatePrimaryEmail']);
        Route::post('update-doctor-primary-email', [DoctorController::class, 'updatePrimaryEmail']);

        // Fee routes
        Route::post('doctor-fee-list/{id}', [DoctorController::class, 'doctorFee']);
        Route::post('add-doctor-fee', [DoctorController::class, 'addDoctorFee']);
        Route::post('update-doctor-fee-status/{id}', [DoctorController::class, 'updateDoctorFeeStatus']);

        // Slot routes
        Route::post('list-of-slots/{id}', [DoctorController::class, 'listOfSlots']);
        Route::post('add-slot', [DoctorController::class, 'addSlot']);
        Route::post('publish-slot', [DoctorController::class, 'publishSlot']);
        Route::post('deactivate-slot', [DoctorController::class, 'deactivateSlot']);

        // Off dates routes
        Route::get('list-of-off-dates/{doctor_id}/{pagination}', [DoctorController::class, 'listOfOffDates']);
        Route::post('add-off-dates', [DoctorController::class, 'addOffDates']);
        Route::post('delete-off-dates', [DoctorController::class, 'deleteOffDates']);

        // consent form
        Route::post('add-consent-log', [ConsentFormController::class,  'addConsentLog']);
        Route::get('consent-log-response', [ConsentFormController::class,  'consentLogResponse']);
        Route::get('register-doctor-consent-forms', [ConsentFormController::class,  'registerDoctorConsentForms']);
        Route::get('register-doctor-publish-consent-forms', [ConsentFormController::class,  'registerDoctorPublishedConsentForms']);

        // patient Information
        Route::post('patient-register', [RegisterPatientController::class, 'registerPatient']);
        Route::post('edit-patient-basic-info', [RegisterPatientController::class, 'editPatientBasicInformation']);
        Route::post('set-reference-contact', [PatientHistoryController::class, 'setPatientReferenceContact']);
        Route::get('reference-contacts/{patient_id}', [PatientHistoryController::class, 'allReferenceContact']);
        Route::get('delete-reference-contact/{contact_id}', [PatientHistoryController::class, 'deletePatientReferenceContact']);
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

        Route::get('countries', [PatientHistoryController::class, 'getAllCountry']);
        Route::get('states/{country_id}', [PatientHistoryController::class, 'getCountryState']);
        Route::get('cities/{state_id}', [PatientHistoryController::class, 'getStateCity']);


        // Patient History

        Route::post('set-family-history', [PatientHistoryController::class, 'setPatientFamilyHistory']);
        Route::get('family-history/{patient_id}', [PatientHistoryController::class, 'allFamilyHistory']);
        Route::get('delete-family-history/{history_id}', [PatientHistoryController::class, 'deleteFamilyHistory']);

        Route::post('set-medical-problem', [PatientHistoryController::class, 'setPatientMedicalProblem']);
        Route::get('patient-medical-problem/{patient_id}', [PatientHistoryController::class, 'allPatientMedicalProblem']);
        Route::get('delete-patient-medical-problem/{problem_id}', [PatientHistoryController::class, 'deletePatientMedicalProblem']);

        Route::post('set-patient-surgery-history', [PatientHistoryController::class, 'setPatientSurgicalHistory']);
        Route::get('patient-surgery-history/{patient_id}', [PatientHistoryController::class, 'allPatientSurgicalHistory']);
        Route::get('delete-patient-surgery-history/{surgery_id}', [PatientHistoryController::class, 'deletePatientSurgicalHistory']);

        Route::get('vaccines', [PatientHistoryController::class, 'allVaccine']);
        Route::get('surgeries', [PatientHistoryController::class, 'allSurgery']);

        Route::post('set-patient-vaccine', [PatientHistoryController::class, 'setPatientVaccine']);
        Route::get('patient-vaccine/{patient_id}', [PatientHistoryController::class, 'allPatientVaccine']);
        Route::get('delete-patient-vaccine/{patient_vaccine_id}', [PatientHistoryController::class, 'deletePatientVaccine']);

        Route::get('medical-problems', [PatientHistoryController::class, 'allMedicalProblem']);
        Route::get('relationships', [PatientHistoryController::class, 'allRelationship']);

        Route::post('set-patient-social-history', [PatientHistoryController::class, 'setPatientSocialHistory']);
        Route::get('patient-social-history/{patient_id}', [PatientHistoryController::class, 'getPatientSocialHistory']);
        Route::get('delete-patient-social-history/{social_history_id}', [PatientHistoryController::class, 'deletePatientSocialHistory']);

        Route::post('set-patient-allergy', [PatientHistoryController::class, 'setPatientAllergy']);
        Route::get('patient-allergy/{patient_id}', [PatientHistoryController::class, 'getPatientAllergy']);
        Route::get('delete-patient-allergy/{patient_allergy_id}', [PatientHistoryController::class, 'deletePatientAllergy']);
        Route::post('set-patient-identification', [PatientHistoryController::class, 'setPatientIdentification']);

        Route::get('allergies', [PatientHistoryController::class, 'getAllAllergy']);
        Route::get('ethnicities', [PatientHistoryController::class, 'getAllEthnicity']);
        Route::get('races', [PatientHistoryController::class, 'getAllRaces']);
        Route::get('languages', [PatientHistoryController::class, 'getAllLanguages']);
        Route::get('routes', [PatientHistoryController::class, 'getAllRoutes']);
        Route::get('sites', [PatientHistoryController::class, 'getAllSite']);
        Route::get('vaccine-manufacture/{vaccine_id}', [PatientHistoryController::class, 'getVaccineManufacture']);
        Route::get('vaccine-ndc/{vaccine_id}', [PatientHistoryController::class, 'getVaccineNationalDrugCode']);

        Route::post('doctor-patient-list', [DoctorController::class, 'doctorPatientList']);
        Route::get('stats', [DoctorController::class, 'doctorDashboardStats']);
        Route::get('appointment-chart', [DoctorController::class, 'doctorAppointmentPiChart']);

        // Notifications
        Route::post('notifications', [DoctorController::class, 'allNotifications']);
        Route::post('notification-read', [DoctorController::class, 'markNotificationAsRead']);
        Route::post('mark-all-notifications-as-read', [DoctorController::class, 'markAllNotificationsAsRead']);

        Route::post('logout', [DoctorController::class, 'logout']);
        // Doctor details by id
        Route::get('{id}', [DoctorController::class, 'getDetailsByID']);

        // Zoom User Create
        Route::post('create-zoom-user', [AppointmentController::class, 'createZoomUser']);
        
        // Patient Appointment History
        Route::post('patient-appointment-list', [AppointmentController::class, 'patientAppointmentListForDoctor']);

    });
});
