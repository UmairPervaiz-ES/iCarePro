<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EPrescription\EPrescriptionController;
use App\Http\Controllers\EPrescription\VitalController;

/*
|--------------------------------------------------------------------------
| ePrescription API Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('practice/vitals')->group(function () {
    Route::middleware(['auth:practice-api'])->group(function () {

        Route::post('/set-blood-pressure', [VitalController::class, 'setBloodPressureVital']);

        Route::post('/set-height', [VitalController::class, 'setHeightVital']);

        Route::post('/set-weight', [VitalController::class, 'setWeightVital']);

        Route::post('/set-heart-rate', [VitalController::class, 'setHeartRateVital']);

        Route::post('/set-respiratory-rate', [VitalController::class, 'setRespiratoryRateVital']);

        Route::post('/set-pulse', [VitalController::class, 'setPulseVital']);

        Route::post('/set-temperature', [VitalController::class, 'setTemperatureVital']);

        Route::post('/patient-vitals', [VitalController::class, 'getPatientVitals']);

        Route::post('/set-pain-scale', [VitalController::class, 'setPainScaleVital']);

        Route::post('/set-inhaled-o2', [VitalController::class, 'setInhaledO2Vital']);

        Route::post('/set-wc', [VitalController::class, 'setWcVital']);

        Route::post('/set-bmi', [VitalController::class, 'setBmivital']);
    });
});

Route::prefix('doctor/vitals')->group(function () {
    Route::middleware(['auth:doctor-api'])->group(function () {

        Route::post('/set-blood-pressure', [VitalController::class, 'setBloodPressureVital']);

        Route::post('/set-height', [VitalController::class, 'setHeightVital']);

        Route::post('/set-weight', [VitalController::class, 'setWeightVital']);

        Route::post('/set-heart-rate', [VitalController::class, 'setHeartRateVital']);

        Route::post('/set-respiratory-rate', [VitalController::class, 'setRespiratoryRateVital']);

        Route::post('/set-pulse', [VitalController::class, 'setPulseVital']);

        Route::post('/set-temperature', [VitalController::class, 'setTemperatureVital']);

        Route::post('/patient-vitals', [VitalController::class, 'getPatientVitals']);

        Route::post('/set-pain-scale', [VitalController::class, 'setPainScaleVital']);

        Route::post('/set-inhaled-o2', [VitalController::class, 'setInhaledO2Vital']);

        Route::post('/set-wc', [VitalController::class, 'setWcVital']);

        Route::post('/set-bmi', [VitalController::class, 'setBmivital']);
    });
});

Route::prefix('doctor/ePrescription')->group(function () {
    Route::middleware(['auth:doctor-api'])->group(function () {

        Route::get('/view/{eId}/', [EPrescriptionController::class, 'viewPrescriptionByEPrescriptionId']);

        Route::get('/viewPatient/{pId}/', [EPrescriptionController::class, 'viewEPrescriptionByPatientId']);

        Route::get('/drugs/{query}/', [EPrescriptionController::class, 'getDrugsForDropdown']);

        Route::get('/lab-tests/{query}/', [EPrescriptionController::class, 'getLabTestsForDropdown']);

        Route::get('/procedure/{query}/', [EPrescriptionController::class, 'getProcedureForDropdown']);

        Route::get('/drug/{drug_id}/', [EPrescriptionController::class, 'getDrugByDrugId']);

        Route::get('/generate-e-prescription-pdf/{appointment_id}', [EPrescriptionController::class, 'generateEPrescription']);

        Route::post('/change-appointment-status', [EPrescriptionController::class, 'changeAppointmentStatus']);

        Route::get('/general-medical-problems', [EPrescriptionController::class, 'generalMedicalProblems']);
    });

    Route::middleware(['auth:doctor-api'])->group(function () {

        Route::Post('/set-drug', [EPrescriptionController::class, 'setDrugToPrescription']);

        Route::get('/remove-drug/{id}', [EPrescriptionController::class, 'removeDrugFromPrescription']);

        Route::Post('/set-lab-test', [EPrescriptionController::class, 'setLabTestToPrescription']);

        Route::get('/remove-lab-test/{id}', [EPrescriptionController::class, 'removeLabTestFromPrescription']);

        Route::Post('/set-procedure', [EPrescriptionController::class, 'setProcedureToPrescription']);

        Route::get('/remove-procedure/{id}', [EPrescriptionController::class, 'removeProcedureFromPrescription']);

        Route::Post('/set-notes', [EPrescriptionController::class, 'setNotesPrescription']);

        Route::post('/set-practice-procedures', [EPrescriptionController::class, 'setPracticeProcedures']);

        Route::post('/set-practice-lab-tests', [EPrescriptionController::class, 'setPracticeLabTests']);

    });
});


Route::prefix('practice/ePrescription')->group(function () {
    Route::middleware(['auth:practice-api'])->group(function () {

        Route::Post('/set-notes', [EPrescriptionController::class, 'setNotesPrescription']);

        Route::get('/view/{eId}/', [EPrescriptionController::class, 'viewPrescriptionByEPrescriptionId']);

        Route::get('/viewPatient/{pId}/', [EPrescriptionController::class, 'viewEPrescriptionByPatientId']);

        Route::get('/drugs/{query}/', [EPrescriptionController::class, 'getDrugsForDropdown']);

        Route::get('/lab-tests/{query}/', [EPrescriptionController::class, 'getLabTestsForDropdown']);

        Route::get('/procedure/{query}/', [EPrescriptionController::class, 'getProcedureForDropdown']);

        Route::get('/drug/{drug_id}/', [EPrescriptionController::class, 'getDrugByDrugId']);

        Route::get('/generate-e-prescription-pdf/{appointment_id}', [EPrescriptionController::class, 'generateEPrescription']);

        Route::post('/change-appointment-status', [EPrescriptionController::class, 'changeAppointmentStatus']);

    });

});


Route::prefix('doctor/drug')->group(function () {
    Route::middleware(['auth:doctor-api'])->group(function () {
        Route::post('/add', [EPrescriptionController::class, 'addDrug']);
    });
});


