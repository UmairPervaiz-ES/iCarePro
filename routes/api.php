<?php

use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\EPrescription\EPrescriptionController;
use App\Http\Controllers\EPrescription\VitalController;
use App\Http\Controllers\gCalendarController;
use App\Http\Controllers\oCalendarController;
use App\Http\Controllers\Patient\PatientHistory\PatientHistoryController;
use App\Http\Controllers\Patient\Register\RegisterPatientController;
use App\Http\Controllers\Practice\Appointment\AppointmentController;
use App\Http\Controllers\Practice\Department\DepartmentController;
use App\Http\Controllers\Practice\Doctor\DoctorDraftController;
use App\Http\Controllers\Practice\Patient\PatientController;
use App\Http\Controllers\Practice\Role\RoleController;
use App\Http\Controllers\Practice\Staff\StaffController;
use App\Http\Controllers\Staff\Appointment\AppointmentController as AppointmentAppointmentController;
use App\Http\Controllers\Staff\Auth\AuthController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Staff API Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return 'API is up and running.';
});

Route::post('doctor-kyc-shuftiPro-response', [\App\Http\Controllers\Practice\Doctor\DoctorController::class, 'kyc_response']);


Route::prefix('staff')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth:api', 'user_type'])->group(function () {
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('logout', [AuthController::class, 'logout']);


          // Staff patient  registration
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

        // Doctor related Routes
        // API when Practice store doctor without creating draft
        Route::post('store-doctor', [\App\Http\Controllers\Practice\Doctor\DoctorController::class, 'store'])->name('staff.store-doctor');
        // API when Practice store doctor details saved as draft
        Route::post('store-doctor-draft', [DoctorDraftController::class, 'store'])->name('staff.store-doctor-draft');
        // API when Practice adds doctors details as draft
        Route::post('store-doctor-as-draft', [DoctorDraftController::class, 'draftCheck'])->name('staff.store-doctor-as-draft');

        // API when Practice adds only name, primary_email, primary phone number, gender, dob and send link to doctor to add his details.
        Route::post('send-doctor-registration-link', [\App\Http\Controllers\Practice\Doctor\DoctorController::class, 'sendRegistrationLinkToDoctor'])->name('staff.send-registration-link-to-doctor');

        // Practice requests for doctor
        Route::get('list-of-registration-requests/{pagination}', [\App\Http\Controllers\Practice\Doctor\DoctorController::class, 'listOfDoctorRequestsSent']);

        // List of Doctors
        Route::post('doctors', [\App\Http\Controllers\Practice\Doctor\DoctorController::class, 'listOfDoctors'])->name('staff.listOfDoctors');

        // Specific doctor
        Route::get('doctor/{id}', [\App\Http\Controllers\Practice\Doctor\DoctorController::class, 'doctorByID'])->name('staff.doctorByID');

        // Calendar appointments view for practice
        Route::post('doctor-dated-appointments', [\App\Http\Controllers\Practice\Doctor\DoctorController::class, 'calendarAppointmentsViewDates']);

        // Checking whether staff is of same practice under which he is registered by middleware (practice_cross_check)
//        Route::middleware(['practice_cross_check'])->group(function () {
            // Personal information of specific doctor update
            Route::post('update-personal-information', [DoctorController::class, 'updatePersonalInformation']);
            Route::post('update-specialization', [DoctorController::class, 'updateSpecialization']);

            // Update about me
            Route::post('update-about-me', [DoctorController::class, 'updateAboutMe']);

            // Update Contact information
            Route::post('update-contact-information', [DoctorController::class, 'updateContactInformation']);

            // Update current address
            Route::post('update-current-address', [DoctorController::class, 'updateCurrentAddress']);

            // Update doctor additional documents
            Route::post('delete-document', [DoctorController::class, 'deleteDocument']);
            Route::post('upload-document', [DoctorController::class, 'uploadDocument']);
            // Doctor fee routes
            Route::post('doctor-fee-list/{id}', [DoctorController::class, 'doctorFee']);
            Route::post('add-doctor-fee', [DoctorController::class, 'addDoctorFee']);
            Route::post('update-doctor-fee-status/{id}', [DoctorController::class, 'updateDoctorFeeStatus']);

            // Slots of specific doctors
            Route::post('list-of-slots/{id}', [DoctorController::class, 'listOfSlots']);
            Route::post('add-slot', [DoctorController::class, 'addSlot']);
            Route::post('publish-slot', [DoctorController::class, 'publishSlot']);
            Route::post('deactivate-slot', [DoctorController::class, 'deactivateSlot']);

            // Off dates routes
            Route::get('list-of-off-dates/{doctor_id}/{pagination}', [DoctorController::class, 'listOfOffDates']);
            Route::post('add-off-dates', [DoctorController::class, 'addOffDates']);
            Route::post('delete-off-dates', [DoctorController::class, 'deleteOffDates']);
//        });

        // Appointment
        Route::post('doctor', [AppointmentAppointmentController::class, 'practiceDoctor']);
        Route::post('doctor-specializations-list', [AppointmentAppointmentController::class, 'doctorSpecializationsList']);
        Route::post('doctor-slot/{id}', [AppointmentAppointmentController::class, 'doctorSlot']);
        Route::post('specialization-list', [AppointmentAppointmentController::class, 'specializationList']);
        Route::post('specialization-doctor', [AppointmentAppointmentController::class, 'specializationsWithDoctor']);
        Route::post('create-appointment', [AppointmentAppointmentController::class, 'createAppointment']);
        Route::post('appointment-list', [AppointmentAppointmentController::class, 'appointmentList']);
        Route::post('re-schedule', [AppointmentAppointmentController::class, 'reSchedule']);
        Route::post('patient-list', [\App\Http\Controllers\Doctor\Appointment\AppointmentController::class, 'patientList']);
        Route::get('medical-problem-list', [AppointmentAppointmentController::class, 'medicalProblemList']);
        Route::post('appointment-date', [AppointmentAppointmentController::class, 'getAppointmentByIdAndDate']);

        // Dashboard
        Route::get('stats', [AppointmentController::class, 'practiceStats']);
        Route::get('appointment-chart', [AppointmentController::class, 'practiceAppointmentPiChart']);
        Route::get('appointment-spline-graph', [AppointmentController::class, 'appointmentSplineGraph']);

        Route::post('appointments-count', [AppointmentController::class, 'appointmentListMonthlyCount']);
        Route::post('upcoming-appointments-list', [AppointmentController::class, 'upcomingAppointmentList']);

        // Doctor relate appointment list show
        Route::post('doctor-appointment-list', [DoctorController::class,  'doctorAppointmentList']);
        Route::get('doctor-pending-list', [\App\Http\Controllers\Practice\Doctor\DoctorController::class, 'doctorPendingList'])->name('staff.doctorPendingList');
        Route::post('doctor-pending-response/{id}', [\App\Http\Controllers\Practice\Doctor\DoctorController::class, 'doctorPendingListResponse'])->name('staff.doctorPendingListResponse');

        // Appointment list by Patient
        Route::post('patient-appointment-details', [PatientController::class,  'patientAppointmentDetails']);
        Route::post('patient-appointment-list', [PatientController::class,  'patientAppointmentList']);

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

        //E-Prescription template
        Route::get('/ePrescription/template-data', [EPrescriptionController::class, 'ePrescriptionTemplateData']);
        Route::post('/ePrescription/set-template-data', [EPrescriptionController::class, 'setEPrescriptionTemplateData']);

        // Staff route
        Route::post('staff', [StaffController::class,  'viewDetailsByStaffID']);
        Route::post('staffs', [StaffController::class,  'listOfStaff']);
        Route::post('add-staff', [StaffController::class,  'store']);
        Route::post('send-credentials-mail-to-staff', [StaffController::class,  'emailWithCredentials']);
        Route::post('staff-status-update', [StaffController::class,  'statusUpdate']);

        // vitals routes
        Route::prefix('vitals')->group(function () {
            Route::post('set-blood-pressure', [VitalController::class, 'setBloodPressureVital']);
            Route::post('set-height', [VitalController::class, 'setHeightVital']);
            Route::post('set-weight', [VitalController::class, 'setWeightVital']);
            Route::post('set-heart-rate', [VitalController::class, 'setHeartRateVital']);
            Route::post('set-respiratory-rate', [VitalController::class, 'setRespiratoryRateVital']);
            Route::post('set-pulse', [VitalController::class, 'setPulseVital']);
            Route::post('set-temperature', [VitalController::class, 'setTemperatureVital']);
            Route::post('patient-vitals', [VitalController::class, 'getPatientVitals']);
            Route::post('set-pain-scale', [VitalController::class, 'setPainScaleVital']);
            Route::post('set-inhaled-o2', [VitalController::class, 'setInhaledO2Vital']);
            Route::post('set-wc', [VitalController::class, 'setWcVital']);
            Route::post('set-bmi', [VitalController::class, 'setBmivital']);
        });

        Route::prefix('ePrescription')->group(function () {

            Route::get('view/{eId}/', [EPrescriptionController::class, 'viewPrescriptionByEPrescriptionId']);

            Route::get('viewPatient/{pId}/', [EPrescriptionController::class, 'viewEPrescriptionByPatientId']);

            Route::get('drugs/{query}/', [EPrescriptionController::class, 'getDrugsForDropdown']);

            Route::get('lab-tests/{query}/', [EPrescriptionController::class, 'getLabTestsForDropdown']);

            Route::get('procedure/{query}/', [EPrescriptionController::class, 'getProcedureForDropdown']);

            Route::get('drug/{drug_id}/', [EPrescriptionController::class, 'getDrugByDrugId']);

            Route::get('generate-e-prescription-pdf/{appointment_id}', [EPrescriptionController::class, 'generateEPrescription']);

            Route::post('change-appointment-status', [EPrescriptionController::class, 'changeAppointmentStatus']);
        });

        // e-prescription routes
        Route::get('/generate-e-prescription-pdf/{appointment_id}', [EPrescriptionController::class, 'generateEPrescription']);
    });
});

// get user email
Route::post('doctor-email', [oCalendarController::class, 'getUserEmail']);
Route::post('gdoctor-email', [gCalendarController::class, 'getUserEmail']);
Route::post('patient-email', [oCalendarController::class, 'getPatientEmail']);
Route::post('gpatient-email', [gCalendarController::class, 'getPatientEmail']);

/// remove sync calendar
Route::post('remove-google-calendar', [gCalendarController::class, 'removeGoogleCalendar']);
Route::post('remove-outlook-calendar', [oCalendarController::class, 'removeOutlookCalendar']);


