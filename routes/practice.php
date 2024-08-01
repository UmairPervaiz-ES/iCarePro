<?php

use App\Http\Controllers\ConsentForm\ConsentFormController;
use App\Http\Controllers\Doctor\Appointment\AppointmentController as AppointmentAppointmentController;
use App\Http\Controllers\Insurance\InsuranceController;
use App\Http\Controllers\Patient\PatientHistory\PatientHistoryController;
use App\Http\Controllers\Patient\Register\RegisterPatientController;
use App\Http\Controllers\Practice\Appointment\AppointmentController;
use App\Http\Controllers\Practice\Auth\AuthController;
use App\Http\Controllers\Practice\Department\DepartmentController;
use App\Http\Controllers\Practice\Doctor\DoctorController;
use App\Http\Controllers\Practice\Doctor\DoctorDraftController;
use App\Http\Controllers\Doctor\DoctorController as DoctorDoctorController;
use App\Http\Controllers\Practice\Initial\InitialPracticeController;
use App\Http\Controllers\Practice\Role\RoleController;
use App\Http\Controllers\Practice\Staff\StaffController;
use App\Http\Controllers\Subscription\SubscriptionTransaction\SubscriptionTransactionController;
use App\Http\Controllers\EPrescription\EPrescriptionController;
use App\Http\Controllers\Practice\Patient\PatientController;
use App\Http\Controllers\UtilityController;
use Illuminate\Support\Facades\Route;

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

Route::post('initial-practice', [InitialPracticeController::class, 'initialPractice']);
Route::post('practice-request/{id}', [InitialPracticeController::class, 'practiceRequest']);
Route::post('practice-document/{id}', [InitialPracticeController::class, 'practiceDocument']);
Route::get('practice-document-delete/{id}', [InitialPracticeController::class, 'practiceDocumentDelete']);
Route::post('contact-person-email-check', [InitialPracticeController::class, 'contactPersonEmailCheck']);


Route::post('forget-password', [AuthController::class, 'forgetPassword']);
Route::post('verify-otp/{token}', [AuthController::class, 'verifyOtp']);
Route::post('set-password', [AuthController::class, 'setPassword']);

Route::get('countries', [PatientHistoryController::class, 'getAllCountry']);
Route::get('states/{country_id}', [PatientHistoryController::class, 'getCountryState']);

Route::get('cities/{state_id}', [PatientHistoryController::class, 'getStateCity']);

// List of specializations present in database
Route::get('doctor-specializations', [DoctorController::class, 'doctorSpecializations']);

Route::get('test', function () {
//    event(new \App\Events\SendMessage());
//    \App\Helper\Helper::webNotification();
//    dd(\Carbon\Carbon::parse('2022-10-15')->format('jS M Y'));
//    dd($serial);
    dd('Event Run Successfully.');
});

Route::group(['prefix' => 'practice'], function () {

    //admin route
    Route::post('login', [AuthController::class, 'login']);

    //apply middleware
    Route::middleware(['auth:practice-api', 'user_type'])->group(function () {

        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        Route::post('change-password', [AuthController::class, 'changePassword']);

        // Doctor related Routes
        // API when Practice store doctor without creating draft
        Route::post('store-doctor', [DoctorController::class, 'store'])->name('practice.store-doctor');
        // API when Practice store doctor details saved as draft
        Route::post('store-doctor-draft', [DoctorDraftController::class, 'store'])->name('practice.store-doctor-draft');
        // API when Practice adds doctors details as draft
        Route::post('store-doctor-as-draft', [DoctorDraftController::class, 'draftCheck'])->name('practice.store-doctor-as-draft');

        // API when Practice adds only name, primary_email, primary phone number, gender, dob and send link to doctor to add his details.
        Route::post('send-doctor-registration-link', [DoctorController::class, 'sendRegistrationLinkToDoctor'])->name('practice.send-doctor-registration-link');

        // Practice requests for doctor
        Route::get('list-of-registration-requests/{pagination}', [DoctorController::class, 'listOfDoctorRequestsSent']);

        // List of Doctors
        Route::post('doctors', [DoctorController::class, 'listOfDoctors']);

        // Specific doctor
        Route::get('doctor/{id}', [DoctorController::class, 'doctorByID']);

        // Calendar appointments view for practice
        Route::post('doctor-dated-appointments', [DoctorController::class, 'calendarAppointmentsViewDates']);

        // Checking whether practice is accessing his own doctor information by middleware (practice_cross_check)
//        Route::middleware(['practice_cross_check'])->group(function () {
            // Personal information of specific doctor update
            Route::post('update-personal-information', [DoctorDoctorController::class, 'updatePersonalInformation']);
            Route::post('update-specialization', [DoctorDoctorController::class, 'updateSpecialization']);

            // Update about me
            Route::post('update-about-me', [DoctorDoctorController::class, 'updateAboutMe']);

            // Update Contact information
            Route::post('update-contact-information', [DoctorDoctorController::class, 'updateContactInformation']);

            // Update current address
            Route::post('update-current-address', [DoctorDoctorController::class, 'updateCurrentAddress']);

            // Update doctor additional documents
            Route::post('delete-document', [DoctorDoctorController::class, 'deleteDocument']);
            Route::post('upload-document', [DoctorDoctorController::class, 'uploadDocument']);
            // Doctor fee routes
            Route::post('doctor-fee-list/{id}', [DoctorDoctorController::class, 'doctorFee']);
            Route::post('/add-doctor-fee', [DoctorDoctorController::class, 'addDoctorFee']);
            Route::post('/update-doctor-fee-status/{id}', [DoctorDoctorController::class, 'updateDoctorFeeStatus']);

            // Slots of specific doctors
            Route::post('list-of-slots/{id}', [DoctorDoctorController::class, 'listOfSlots']);
            Route::post('add-slot', [DoctorDoctorController::class, 'addSlot']);
            Route::post('publish-slot', [DoctorDoctorController::class, 'publishSlot']);
            Route::post('deactivate-slot', [DoctorDoctorController::class, 'deactivateSlot']);

            // Off dates routes
            Route::get('list-of-off-dates/{doctor_id}/{pagination}', [DoctorDoctorController::class, 'listOfOffDates']);
            Route::post('add-off-dates', [DoctorDoctorController::class, 'addOffDates']);
            Route::post('delete-off-dates', [DoctorDoctorController::class, 'deleteOffDates']);
//        });

        // Appointment
        Route::post('doctor', [AppointmentController::class, 'practiceDoctor']);
        Route::post('doctor-specializations-list', [AppointmentController::class, 'doctorSpecializationsList']);
        Route::post('doctor-slot/{doctor_id}', [AppointmentController::class, 'doctorSlot']);
        Route::post('specialization-list', [AppointmentController::class, 'specializationList']);
        Route::post('specialization-doctor', [AppointmentController::class, 'specializationsWithDoctor']);
        Route::post('create-appointment', [AppointmentController::class, 'createAppointment']);
        Route::post('appointment-list', [AppointmentController::class, 'appointmentList']);
        Route::post('re-schedule', [AppointmentController::class, 'reSchedule']);
        Route::post('patient-list', [AppointmentAppointmentController::class, 'patientList']);
        Route::get('/medical-problem-list', [AppointmentAppointmentController::class, 'medicalProblemList']);
        Route::post('appointment-date', [AppointmentController::class,  'getAppointmentByIdAndDate']);

        // Dashboard
        Route::get('stats', [AppointmentController::class, 'practiceStats']);
        Route::get('appointment-chart', [AppointmentController::class, 'practiceAppointmentPiChart']);
        Route::get('appointment-spline-graph', [AppointmentController::class, 'appointmentSplineGraph']);

        Route::post('appointments-count', [AppointmentController::class, 'appointmentListMonthlyCount']);
        Route::post('upcoming-appointments-list', [AppointmentController::class, 'upcomingAppointmentList']);

        // Doctor relate appointment list show
        Route::post('doctor-appointment-list', [DoctorController::class,  'doctorAppointmentList']);
        Route::get('doctor-pending-list', [DoctorController::class,  'doctorPendingList']);
        Route::post('doctor-pending-response/{id}', [DoctorController::class,  'doctorPendingListResponse']);

        // Insurance
        Route::post('add-insurance', [InsuranceController::class,  'addInsurance']);
        Route::get('insurance-list/{id}', [InsuranceController::class,  'insuranceList']);
        Route::get('insurance-company-list/{id}', [InsuranceController::class,  'InsuranceCompanyList']);

        // Appointment list by Patient
        Route::post('patient-appointment-details', [PatientController::class,  'patientAppointmentDetails']);
        Route::post('patient-appointment-list', [PatientController::class,  'patientAppointmentList']);

        // consent form
        Route::post('set-consent-form-type', [ConsentFormController::class,  'setConsentFormType']);
        Route::post('set-consent-form', [ConsentFormController::class,  'setConsentForm']);
        Route::get('consent-forms', [ConsentFormController::class,  'consentForms']);
        Route::get('publish-consent-forms', [ConsentFormController::class,  'publishedConsentForms']);
        Route::post('add-consent-log', [ConsentFormController::class,  'addConsentLog']);
        Route::get('register-doctor-consent-forms', [ConsentFormController::class,  'registerDoctorConsentForms']);
        Route::get('register-doctor-publish-consent-forms', [ConsentFormController::class,  'registerDoctorPublishedConsentForms']);
        Route::get('register-patient-consent-forms', [ConsentFormController::class,  'registerPatientConsentForms']);
        Route::get('register-patient-publish-consent-forms', [ConsentFormController::class,  'registerPatientPublishedConsentForms']);


        // Department Route
        Route::get('departments', [DepartmentController::class,  'list']);
        Route::post('add-department', [DepartmentController::class,  'store']);
        Route::post('edit-department', [DepartmentController::class,  'edit']);
        Route::post('add-department-employee-type', [DepartmentController::class,  'departmentEmployeeType']);
        Route::post('department-employee-type-status-update/{id}', [DepartmentController::class,  'departmentEmployeeTypeUpdateStatus']);

        // Role and permissions
        Route::get('roles', [RoleController::class,  'list']);
        Route::get('roles-pagination/{noOfRecords}', [RoleController::class,  'rolesPagination']);
        Route::post('add-role', [RoleController::class,  'addRole']);
        Route::post('assign-permissions-to-role', [RoleController::class,  'assignPermissions']);
        Route::get('permissions', [RoleController::class,  'permissions']);

        // Staff route
        Route::post('staff', [StaffController::class,  'viewDetailsByStaffID']);
        Route::post('staffs', [StaffController::class,  'listOfStaff']);
        Route::post('add-staff', [StaffController::class,  'store']);
        Route::post('send-credentials-mail-to-staff', [StaffController::class,  'emailWithCredentials']);
        Route::post('staff-status-update', [StaffController::class,  'statusUpdate']);

        // practice patient  registration
        Route::post('patient-register', [RegisterPatientController::class, 'registerPatient']);
        Route::post('check-patient', [RegisterPatientController::class, 'checkPatientExist']);
        Route::post('check-patient-login', [RegisterPatientController::class, 'checkPatientLogin']);
        Route::post('practice-patient/{practice_id}', [PatientHistoryController::class, 'getPracticePatient']);
        Route::get('patient/{patient_id}', [PatientHistoryController::class, 'getPatient']);
        // patient Information
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

        // route for buying a subscription
        Route::post('buy-subscription', [SubscriptionTransactionController::class, 'buySubscription']);
        // Add New Payment Method On Stripe Against Customer API
        Route::post('add-new-payment-method-on-stripe-against-customer', [SubscriptionTransactionController::class, 'addNewPaymentMethodOnStripeAgainstCustomer']);
        // Change default payment card api
        Route::post('change-default-payment-method', [SubscriptionTransactionController::class, 'changeDefaultPaymentMethod']);
        // Change the Subscription Plan (Purchase API)
        Route::post('change-subscription', [SubscriptionTransactionController::class, 'changeSubscription']);

        Route::post('/set-practice-procedures', [EPrescriptionController::class, 'setPracticeProcedures']);
        Route::get('/remove-practice-procedures/{id}', [EPrescriptionController::class, 'removePracticeProcedures']);
        Route::post('/set-practice-lab-tests', [EPrescriptionController::class, 'setPracticeLabTests']);
        Route::get('/remove-practice-lab-tests/{id}', [EPrescriptionController::class, 'removePracticeLabTests']);

        //E-Prescription template
        Route::get('/ePrescription/template-data', [EPrescriptionController::class, 'ePrescriptionTemplateData']);
        Route::post('/ePrescription/set-template-data', [EPrescriptionController::class, 'setEPrescriptionTemplateData']);

        //Google Analytics
        Route::get('/analytics-data', [UtilityController::class, 'getGoogleAnalytics']);

        // Notifications
        Route::post('notifications', [InitialPracticeController::class, 'allNotifications']);
        Route::post('notification-read', [InitialPracticeController::class, 'markNotificationAsRead']);
        Route::post('mark-all-notifications-as-read', [InitialPracticeController::class, 'markAllNotificationsAsRead']);

        //HL7 Message
        Route::get('/hl7-message', [UtilityController::class, 'hLMessage']);
    });
});
