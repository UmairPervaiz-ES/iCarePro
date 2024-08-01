<?php

namespace App\Repositories\EPrescription\Eloquent\EPrescription;

use App\Jobs\Appointment\AppointmentStatusChanged;
use App\Jobs\Doctor\PatientCheckedInNotification;
use App\Jobs\Patient\AppointmentCancelledNotification;
use App\Jobs\Patient\EPrescriptionGenerated;
use App\libs\Messages\EPrescriptionGlobalMessageBook as EGMBook;
use App\Notifications\Doctor\PatienCheckedIn;
use App\Notifications\Patient\AppointmentCancelled;
use App\Notifications\Patient\EPrescriptionGeneratedNotification;
use App\Models\{
    Appointment\Appointment,
    EPrescription\Drug,
    EPrescription\EPrescription,
    EPrescription\LabTest,
    EPrescription\PrescribedDrug,
    EPrescription\PrescribedLabTest,
    EPrescription\PrescribedProcedure,
    EPrescription\Procedure
};
use App\Models\Patient\Patient;
use App\Repositories\EPrescription\Interfaces\EPrescription\EPrescriptionRepositoryInterface;
use App\Traits\{CheckVEAuth, CreateOrUpdate, FileUpload, IdentifyCreateOrUpdate, RespondsWithHttpStatus};
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use PDF;
use App\Jobs\SendEPrescriptionJob;
use Illuminate\Support\Facades\File;
use App\Models\Doctor\Doctor;
use Illuminate\Support\Facades\DB;
use App\Jobs\Appointment\DoctorAppointment as AppointmentDoctorAppointment;
use App\Jobs\Appointment\PatientAppointment as AppointmentPatientAppointment;
use App\Models\EPrescription\DrugStrength;
use App\Models\EPrescription\TemplateData;
use App\Models\Patient\MedicalProblem;

class EPrescriptionRepository implements EPrescriptionRepositoryInterface
{
    use RespondsWithHttpStatus;
    use CreateOrUpdate;
    use IdentifyCreateOrUpdate;
    use CheckVEAuth;
    use FileUpload;

    public function __construct(Request $request)
    {
        $this->key = ['id' => $request->id];
        $this->request = request()->all();
    }

    /**
     * Description: Add Drugs in e-prescription
     * 1) If Appointment doesn't exist. It will return error
     * 2) If Prescribed Drug exist. It will be updated
     * 3) Activity is logged
     * 4) Prescribed Drug and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setDrugToPrescription($request)
    {
        $checkEPrescriptionAuth = $this->checkEPrescriptionAuth($request);
        if (!$checkEPrescriptionAuth['patient_id']) {
            $response = false;
            $message = EGMBook::FAILED['APPOINTMENT_NOT_FOUND'];
            $status = 400;
            $success = false;
        } else {
            $data = [
                'patient_id' => $checkEPrescriptionAuth['patient_id'],
                'practice_id' => $checkEPrescriptionAuth['practice_id'],
                'doctor_id' => auth()->id(),
                'appointment_id' => $request['appointment_id'],
                'medical_problem_id' => $request['medical_problem_id'],
                'drug_id' => $request['drug_id'],
                'drug_name' => $request['drug_name'],
                'strength_id' => $request['strength_id'],
                'strength_value' => $request['strength_value'],
                'quantity' => $request['quantity'],
                'mg_tab' => $request['mg_tab'],
                'repetition' => $request['repetition'],
                'route' => $request['route'],
                'type' => $request['type'],
                'when' => $request['when'],
                'quantity_unit' => $request['quantity_unit'],
                'for_days' => $request['for_days'],
                'quantity_total' => $request['quantity_total'],
                'internal_note' => $request['internal_note'],
                'note_to_patient' => $request['note_to_patient'],
                'note_to_pharmacy' => $request['note_to_pharmacy'],
                'dispense_as_written' => $request['dispense_as_written'],
            ];
            $response = $this->createOrUpdate('EPrescription\PrescribedDrug', $data,  $this->key);
            $message = EGMBook::SUCCESS['SET_DRUG_TO_PRESCRIPTION'];
            $status = 201;
            $success = true;
        }
        return $this->response($this->request, $response, $message, $status, $success);
    }

    /**
     * Description: Remove Drug from e-prescription
     * 1) If drug doesn't exist. It will return error
     * 2) Activity is logged
     * 3) Drug and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function removeDrugFromPrescription($request)
    {
        $request = ['id' => $request];
        Validator::make($request, ['id' => 'numeric|min:1',]);
        $prescribedDrug  =  PrescribedDrug::find($request['id']);
        if (!$prescribedDrug) {
            $response = false;
            $message = EGMBook::FAILED['APPOINTMENT_NOT_FOUND'];
            $status = 400;
            $success = false;
        } else {
            $prescribedDrug->delete();
            $response = $prescribedDrug;
            $message = EGMBook::SUCCESS['PRESCRIBED_DRUG_REMOVE'];
            $status = 201;
            $success = true;
        }
        return $this->response($this->request, $response, $message, $status, $success);
    }

    /**
     * Description: Add Lab test in e-prescription
     * 1) If lab test doesn't exist. It will return error
     * 2) If lab test exist. It will be updated
     * 3) Activity is logged
     * 4) Lab test and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setLabTestToPrescription($request)
    {
        $checkEPrescriptionAuth = $this->checkEPrescriptionAuth($request);
        if (!$checkEPrescriptionAuth['patient_id']) {
            $response = false;
            $message = EGMBook::FAILED['LAB_TEST_NOT_FOUND'];
            $status = 400;
            $success = false;
        } else {
            $data = [
                'patient_id' => $checkEPrescriptionAuth['patient_id'],
                'practice_id' => $checkEPrescriptionAuth['practice_id'],
                'doctor_id' => auth()->id(),
                'appointment_id' => $request['appointment_id'],
                'medical_problem_id' => $request['medical_problem_id'],
                'lab_test_name' => $request['lab_test_name'],
                'lab_test_id' => $request['lab_test_id'],
            ];
            $response = $this->createOrUpdate('EPrescription\PrescribedLabTest', $data,  $this->key);
            $response->load('labTest:id,name,price');
            $message = EGMBook::SUCCESS['SET_LAB_TEST_TO_PRESCRIPTION'];
            $status = 201;
            $success = true;
        }
        return $this->response($this->request, $response, $message, $status, $success);
    }

    /**
     * Description: Remove lab test from e-prescription
     * 1) If lab test doesn't exist. It will return error
     * 2) Activity is logged
     * 3) Lab test and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function removeLabTestFromPrescription($request)
    {
        $request = ['id' => $request];
        Validator::make($request, ['id' => 'numeric|min:1']);
        $prescribedLabTest  =  PrescribedLabTest::find($request['id']);
        if (!$prescribedLabTest) {
            $response = $prescribedLabTest;
            $message = EGMBook::FAILED['PRESCRIBED_LAB_TEST_NOT_FOUND'];
            $status = 400;
            $success = false;
        } else {
            $prescribedLabTest->delete();
            $response = $prescribedLabTest;
            $message = EGMBook::SUCCESS['PRESCRIBED_LAB_TEST_REMOVE'];
            $status = 201;
            $success = true;
        }
        return $this->response($this->request, $response, $message, $status, $success);
    }

    /**
     * Description: Add Procedure in e-prescription
     * 1) If Procedure doesn't exist. It will return error
     * 2) If Procedure exist. It will be updated
     * 3) Activity is logged
     * 4) Procedure and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setProcedureToPrescription($request)
    {
        $checkEPrescriptionAuth = $this->checkEPrescriptionAuth($request);
        if (!$checkEPrescriptionAuth['patient_id']) {
            $response = false;
            $message = EGMBook::FAILED['APPOINTMENT_NOT_FOUND'];
            $status = 400;
            $success = false;
        } else {
            $data = [
                'patient_id' => $checkEPrescriptionAuth['patient_id'],
                'practice_id' => $checkEPrescriptionAuth['practice_id'],
                'doctor_id' => auth()->id(),
                'appointment_id' => $request['appointment_id'],
                'medical_problem_id' => $request['medical_problem_id'],
                'procedure_id' => $request['procedure_id'],
                'procedure_name' => $request['procedure_name'],
            ];
            $response = $this->createOrUpdate('EPrescription\PrescribedProcedure', $data,  $this->key);
            $response->load('procedure:id,name,price,description');
            $message = EGMBook::SUCCESS['SET_PROCEDURE_TO_PRESCRIPTION'];
            $status = 201;
            $success = true;
        }
        return $this->response($this->request, $response, $message, $status, $success);
    }

    /**
     * Description: Remove Procedure from e-prescription
     * 1) If procedure doesn't exist. It will return error
     * 2) Activity is logged
     * 3) Procedure and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function removeProcedureFromPrescription($request)
    {
        $request = ['id' => $request];
        Validator::make($request, ['id' => 'numeric|min:1',]);
        $prescribedProcedure  =  PrescribedProcedure::find($request['id']);
        if (!$prescribedProcedure) {
            $response = $prescribedProcedure;
            $message = EGMBook::FAILED['PRESCRIBED_PROCEDURE_NOT_FOUND'];
            $status = 400;
            $success = false;
        } else {
            $prescribedProcedure->delete();
            $response = $prescribedProcedure;
            $message = EGMBook::SUCCESS['PRESCRIBED_PROCEDURE_REMOVE'];
            $status = 201;
            $success = true;
        }
        return $this->response($this->request, $response, $message, $status, $success);
    }

    /**
     * Description: Add Notes in e-prescription
     * 1) If appointment doesn't exist. It will return error
     * 2) Activity is logged
     * 3) Notes and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setNotesPrescription($request)
    {
        $checkEPrescriptionAuth = $this->checkEPrescriptionAuth($request);
        $key = ['appointment_id' => $request['appointment_id']];
        if (!$checkEPrescriptionAuth['patient_id']) {
            $response = false;
            $message = EGMBook::FAILED['APPOINTMENT_NOT_FOUND'];
            $status = 400;
            $success = false;
        } else {
            $data = [
                'patient_id' => $checkEPrescriptionAuth['patient_id'],
                'practice_id' => $checkEPrescriptionAuth['practice_id'],
                'doctor_id' => auth()->id(),
                'appointment_id' => $request['appointment_id'],
                'notes' => $request['notes'],
            ];
            $response = $this->createOrUpdate('EPrescription\EPrescription', $data,  $key);
            $message = EGMBook::SUCCESS['SET_Notes_TO_PRESCRIPTION'];
            $status = 201;
            $success = true;
        }
        return $this->response($this->request, $response, $message, $status, $success);
    }

    /**
     * Description: Create/Update Procedures
     * 1) If Procedure exist. It will be updated
     * 2) Activity is logged
     * 3) Procedure and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setPracticeProcedures($request)
    {
        $checkEPrescriptionAuth = $this->checkEPrescriptionAuth($request);
        $data = [
            'practice_id' => $checkEPrescriptionAuth['practice_id'],
            'name' => $request['name'],
            'price' => $request['price'],
            'description' =>  $request['description'],
        ];
        $response = $this->createOrUpdate('EPrescription\Procedure', $data, $this->key);
        return $this->response(request()->all(), $response, EGMBook::SUCCESS['SET_PROCEDURE'], 201);
    }

    /**
     * Description: Remove Procedure
     * 1) If procedure doesn't exist. It will return error
     * 2) Activity is logged
     * 3) Procedure and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function removePracticeProcedures($request)
    {
        $request = ['id' => $request];
        Validator::make($request, ['id' => 'numeric|min:1',]);
        $procedure  =  Procedure::find($request['id']);
        if (!$procedure) {
            $response = false;
            $message = EGMBook::FAILED['PROCEDURE_NOT_FOUND'];
            $status = 400;
            $success = false;
        } else {
            $procedure->delete();
            $response = $procedure;
            $message = EGMBook::SUCCESS['PROCEDURE_REMOVE'];
            $status = 201;
            $success = true;
        }
        return $this->response($this->request, $response, $message, $status, $success);
    }

    /**
     * Description: Create/Update Lab test
     * 1) If Lab test exist. It will be updated
     * 2) Activity is logged
     * 3) Lab test and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setPracticeLabTests($request)
    {
        $checkEPrescriptionAuth = $this->checkEPrescriptionAuth($request);

        $data = [
            'practice_id' => $checkEPrescriptionAuth['practice_id'],
            'name' => $request['name'],
            'price' => $request['price'],
            'description' =>  $request['description'],
        ];

        $response = $this->createOrUpdate('EPrescription\LabTest', $data,  $this->key);

        return $this->response(request()->all(), $response, EGMBook::SUCCESS['SET_LAB_TEST'], 201);
    }

    /**
     * Description: Remove Lab test
     * 1) If Lab test doesn't exist. It will return error
     * 2) Activity is logged
     * 3) Lab test and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function removePracticeLabTests($request)
    {
        $request = ['id' => $request];
        Validator::make($request, ['id' => 'numeric|min:1']);
        $labTest  =  LabTest::find($request['id']);
        if (!$labTest) {
            $response = false;
            $message = EGMBook::FAILED['LAB_TEST_NOT_FOUND'];
            $status = 400;
            $success = false;
        } else {
            $labTest->delete();
            $response = $labTest;
            $message = EGMBook::SUCCESS['LAB_TEST_REMOVE'];
            $status = 201;
            $success = true;
        }
        return $this->response($this->request, $response, $message, $status, $success);
    }

    /**
     * Description: View e-prescriptions by patient id
     * 1) E-prescriptions with pagination return
     *
     * @param  mixed $request
     * @return void
     */
    public function viewEPrescriptionByPatientId($request)
    {
        $prescription  =  EPrescription::where('patient_id', $request)->paginate('15');
        return $this->response($request, $prescription, EGMBook::SUCCESS['E_PRESCRIPTION_PID_FETCHED'], 200, true);
    }

    /**
     * Description: View e-prescriptions by e-prescription id
     * 1) E-prescription return
     *
     * @param $id
     * @return Response
     */
    public function viewPrescriptionByEPrescriptionId($id): Response
    {
        $prescription = EPrescription::where('appointment_id', $id)
            ->with(
                'prescribedDrugs.drug',
                'prescribedDrugs.drugStrength',
                'prescribedDrugs.allDrugStrength',
                'prescribedLabTests.labTest:id,name,price',
                'prescribedProcedures.procedure:id,name,price,description'
            )->first();
        return $this->response($id, $prescription, EGMBook::SUCCESS['E_PRESCRIPTION_EID_FETCHED'], 200);
    }

    /**
     * Description: Get Drugs by drug id
     * 1) Drug return
     *
     * @param  mixed $request
     * @return void
     */
    public function getDrugByDrugId($request)
    {
        $request = ['id' => $request];
        Validator::make($request, ['id' => 'numeric|min:1']);
        $drugs = Drug::where('id', $request['id'])->with('drugStrength', 'manufacturer',)->first();
        return $this->response($request, $drugs, EGMBook::SUCCESS['DRUG_DID'], 200, true);
    }

    /**
     * Description: Get Drugs for search by keyword
     * 1) Drug return
     *
     * @param  mixed $request
     * @return void
     */
    public function getDrugsForDropdown($request)
    {
        $request = ['name' => $request];
        Validator::make($request, ['name' => 'string|min:2']);
        $drugs = Drug::select('id', 'name')->where('name', 'ILIKE', "%{$request['name']}%")->with('manufacturer')->get();
        return $this->response($request, $drugs, EGMBook::SUCCESS['GET_DRUG'], 200, true);
    }

    /**
     * Description: Get Lab test for search by keyword
     * 1) Lab test return
     *
     * @param  mixed $request
     * @return void
     */
    public function getLabTestsForDropdown($request)
    {
        $request = ['name' => $request];
        $checkEPrescriptionAuth = $this->checkEPrescriptionAuth($request);
        Validator::make($request, ['name' => 'string|min:2']);
        $labTest = LabTest::select('id', 'name')->where('name', 'ILIKE', "%{$request['name']}%")->where('practice_id', $checkEPrescriptionAuth['practice_id'])->get();
        return $this->response($request, $labTest, EGMBook::SUCCESS['GET_LAB_TEST'], 200, true);
    }

    /**
     * Description: Get Procedure search by keyword
     * 1) Procedure return
     *
     * @param  mixed $request
     * @return void
     */
    public function getProcedureForDropdown($request)
    {
        $checkEPrescriptionAuth = $this->checkEPrescriptionAuth($request);
        $request = ['name' => $request];
        Validator::make($request, ['name' => 'string|min:2']);
        $procedure = Procedure::select('id', 'name')->where('name', 'ILIKE', "%{$request['name']}%")->where('practice_id', $checkEPrescriptionAuth['practice_id'])->get();
        return $this->response($request, $procedure, EGMBook::SUCCESS['GET_PROCEDURE'], 200, true);
    }

    /**
     * Description: Generate E-Prescription PDF and Save it on Server
     * 1) Pdf Path return
     * 2) Patient is notified about e-prescription generation
     *
     * @param mixed $request
     * @return Response
     */
    public function generateEPrescription($request): Response
    {
        $appointment_id = $request;
        $request = ['appointment_id' => $request];
        $checkEPrescriptionAuth = $this->checkEPrescriptionAuth($request);
        Validator::make($request, ['appointment_id' => 'numeric']);
        if ($checkEPrescriptionAuth['patient_id']) {
            $patient_key = Patient::where('id', $checkEPrescriptionAuth['patient_id'])->first();
            $folder = storage_path('app/public/e-prescriptions/');
            if (!File::exists($folder)) {
                File::makeDirectory($folder);
            }
            $path = storage_path('app/public/e-prescriptions/' . $patient_key->patient_key);
            $file = $path . '/' . $request['appointment_id'] . '.pdf';
            if (!File::exists($path)) {
                File::makeDirectory($path);
            }
            if (File::exists($file)) {
                File::delete('storage/e-prescriptions/' . $patient_key->patient_key . '/' . $request['appointment_id'] . '.pdf');
            } else {
                $appointment = Appointment::where('id', $request['appointment_id'])->first();
                if (!EPrescription::where('appointment_id', $request['appointment_id'])->exists()) {
                    $data = [
                        'patient_id' => $appointment['patient_id'],
                        'practice_id' => $appointment['practice_id'],
                        'doctor_id' => $appointment['doctor_id'],
                        'appointment_id' => $request['appointment_id'],
                    ];
                    EPrescription::create($data);
                }
            }
            $data['data'] =  EPrescription::where('appointment_id', $appointment_id)
                ->with(
                    'doctor',
                    'doctor.doctorSpecializations.specializations',
                    'practice',
                    'practice.initialPractice',
                    'patient',
                    'prescribedDrugs.drug',
                    'prescribedLabTests',
                    'prescribedProcedures',
                    'prescribedProcedures.procedure',
                    'appointment'
                )->first()->toArray();

            $appointment = Appointment::where('id', $appointment_id)->first();

            $problem_ids = array_map('intval', explode(',', $appointment->medical_problem_id));
            $data['data']['medical_problems'] = MedicalProblem::select('name')->whereIn('id',  $problem_ids)->get()->toArray();

            $data['data']['vitals'] = Patient::where('id', $appointment['patient_id'])
                ->with(
                    [
                        "bloodPressureVital" => function ($q) use ($appointment_id) {
                            $q->where('blood_pressure_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at', 'DESC');
                        },
                        "heightVital" => function ($q) use ($appointment_id) {
                            $q->where('height_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at', 'DESC');
                        },
                        "weightVital" => function ($q) use ($appointment_id) {
                            $q->where('weight_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at', 'DESC');
                        },
                        "heartRateVital" => function ($q) use ($appointment_id) {
                            $q->where('heart_rate_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at', 'DESC');
                        },
                        "pulseVital" => function ($q) use ($appointment_id) {
                            $q->where('pulse_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at', 'DESC');
                        },
                        "respiratoryRateVital" => function ($q) use ($appointment_id) {
                            $q->where('respiratory_rate_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at', 'DESC');
                        },
                        "temperatureVital" => function ($q) use ($appointment_id) {
                            $q->where('temperature_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at', 'DESC');
                        },
                        "painScaleVital" => function ($q) use ($appointment_id) {
                            $q->where('pain_scale_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at', 'DESC');
                        },
                        "inhaledO2Vital" => function ($q) use ($appointment_id) {
                            $q->where('inhaled_o2_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at', 'DESC');
                        },
                        "wcVital" => function ($q) use ($appointment_id) {
                            $q->where('wc_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at', 'DESC');
                        },
                        "bmiVital" => function ($q) use ($appointment_id) {
                            $q->where('bmi_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at', 'DESC');
                        }
                    ]
                )->orderBy('created_at', 'DESC')->first()->toArray();


            $data['templateData'] = TemplateData::where('practice_id', $appointment['practice_id'])->first();
            PDF::loadView('ePrescription.ePrescriptionView', $data)->save($path . '/' . $request['appointment_id'] . '.pdf');
            $details['email'] = $data['data']['patient']['email'];
            $details['patient'] = $patient_key;
            $details['practice_name'] = $data['data']['practice']['initial_practice']['practice_name'];
            $details['doctor_name'] = $data['data']['doctor']['first_name'] . ' ' . $data['data']['doctor']['last_name'];
            $details['appointment_key'] = $data['data']['appointment']['appointment_key'];
            $details['file'] = public_path('storage/e-prescriptions/' . $patient_key->patient_key . '/' . $request['appointment_id'] . '.pdf');

            $patient_key->notify(new EPrescriptionGeneratedNotification($appointment));
            $notification = DatabaseNotification::where(['notifiable_id' => $patient_key->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Patient\Patient')->orderByDesc('created_at')->first();
            $unread_notifications_count = $patient_key->unreadNotifications()->count();
            $total_notifications = $patient_key->notifications()->count();

            dispatch(new EPrescriptionGenerated($appointment, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.E_PRESCRIPTION_GENERATED'));
            dispatch(new SendEPrescriptionJob($details))->onQueue(config('constants.SEND_E_PRESCRIPTION'));

            $message = EGMBook::SUCCESS['PDF_CREATED'];
            $status = 200;
            $success = true;
            $response = config('constants.PRACTICE_URL') . 'backend/download-pdf/' . $patient_key->patient_key . '/' . $request['appointment_id'];
        } else {
            $message =  EGMBook::FAILED['APPOINTMENT_NOT_FOUND'];
            $status = 400;
            $success = false;
            $response = null;
        }
        return $this->response($request, $response, $message, $status, $success);
    }

    /**
     * Description: Change the Appointment Status
     * 1) Appointment Status return
     * 2) Doctor and patient are notified about appointment status
     *
     * @param mixed $request
     * @return Response
     */
    public function changeAppointmentStatus($request): Response
    {
        $checkEPrescriptionAuth = $this->checkEPrescriptionAuth($request);
        if ($checkEPrescriptionAuth['patient_id']) {
            $practice_id = $checkEPrescriptionAuth['practice_id'];
            ($request['status'] == 'Cancelled') ? ($response = Appointment::where('id', $request['appointment_id'])
                ->update(['status' => $request['status'], 'reason' => $request['reason'], 'comments' =>  $request['comments']]))
                : ($response = Appointment::where('id', $request['appointment_id'])->update(['status' => $request['status']]));
            $appointment = Appointment::where('id', $request['appointment_id'])->first();
            $patient = Patient::where('id', $appointment->patient_id)->first();
            $doctor = Doctor::where('id', $appointment->doctor_id)->first();
            $practice = DB::SELECT("SELECT practice_registration_requests.practice_name , practice_addresses.address_line_1, practice_addresses.address_line_2
             FROM practices
             INNER JOIN practice_registration_requests ON practices.practice_registration_request_id = practice_registration_requests.id
             INNER JOIN practice_addresses ON practices.id = practice_addresses.practice_id
             where practices.id = '{$practice_id}';");

            $this->notify($request, $checkEPrescriptionAuth, $appointment);

            // generating event for appointment status update for practice, doctor, patient
//            if (Auth::getDefaultDriver() == 'api' || Auth::getDefaultDriver() == 'practice-api' ||
//                Auth::getDefaultDriver() == 'doctor-api' || Auth::getDefaultDriver() == 'patient-api' )
//            {
                dispatch(new AppointmentStatusChanged($appointment))
                    ->onQueue(config('constants.APPOINTMENT_STATUS_UPDATE_NOTIFICATION'));
//            }

            // send email doctor and patient
            dispatch(new AppointmentPatientAppointment($appointment, $doctor, $patient, $practice, null))
                ->onQueue(config('constants.PATIENT_APPOINTMENT'));
            dispatch(new AppointmentDoctorAppointment($appointment, $doctor, $patient, $practice, null))
                ->onQueue(config('constants.DOCTOR_APPOINTMENT'));
            $message = EGMBook::SUCCESS['APPOINTMENT_STATUS_CHANGE'];
            $status = 200;
            $success = true;
        } else {
            $message =  EGMBook::FAILED['APPOINTMENT_NOT_FOUND'];
            $status = 400;
            $success = false;
            $response = null;
        }
        return $this->response($request, $response, $message, $status, $success);
    }

    /**
     * Description: ePrescription Template Data
     * 1) Get ePrescription Template Data
     *
     * @return void
     */
    public function ePrescriptionTemplateData()
    {
        if (Auth::getDefaultDriver() == "practice-api") {
            $practiceId = auth()->id();
        } elseif (Auth::getDefaultDriver() == "api") {
            $practiceId = auth()->user()->practice_id;
        }
        $message =  EGMBook::FAILED['TEMPLATE_DATA_NOT_FOUND'];
        $templateData = TemplateData::where('practice_id', $practiceId)->first();
        if ($templateData) {
            $message =  EGMBook::FAILED['TEMPLATE_DATA_NOT_FOUND'];
        }
        return $this->response(true, $templateData, $message, 200);
    }

    /**
     * Description: Create/Update Template data
     * 1) If template data exist. It will be updated
     * 2) If cdata exist not exist. It will be created
     * 3) Activity is logged
     * 4) Template data and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setEPrescriptionTemplateData($request)
    {
        $practiceId = $this->practice_id();
        $key = ['id' => $request['id'], 'practice_id' => $practiceId];
        $pathStore = 'practice/logo/' . $practiceId;

        $logoName = $this->fileUpload($request, 'logo', $pathStore);
        $data = [
            'practice_id' => $practiceId,
            'phone' => $request['phone'],
            'country_code' => $request['country_code'],
            'email' => $request['email'],
            'address' => $request['address'],
            'disclaimer' => $request['disclaimer'],
            'color_scheme' => $request['color_scheme'],
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] =  'storage/' . $pathStore . '/' . $logoName;
        }
        // create/update template data
        $templateData = $this->createOrUpdate('EPrescription\TemplateData', $data, $key);

        if ($templateData->wasRecentlyCreated) {
            $responseMessage = EGMBook::SUCCESS['TEMPLATE_DATA_CREATED'];
            $status = 201;
        } else {
            $responseMessage = EGMBook::SUCCESS['TEMPLATE_DATA_UPDATED'];
            $status = 200;
        }
        // Store Activity log. Return response
        return $this->response($request, $templateData, $responseMessage, $status);
    }

    /**
     * Description: Local function used by changeAppointmentStatus function
     * 1) Notifies doctor and patient about appointment status
     *
     * @param  mixed $request
     * @return void
     */
    function notify($request, $checkEPrescriptionAuth, $appointment): void
    {
        if ($request['status'] == 'Cancelled') {
            // Notifying patient about appointment status
            $appointment->patient->notify(new AppointmentCancelled($appointment));
            $notification = DatabaseNotification::where(['notifiable_id' => $appointment->patient->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Patient\Patient')->orderByDesc('created_at')->first();
            $unread_notifications_count = $appointment->patient->unreadNotifications()->count();
            $total_notifications = $appointment->patient->notifications()->count();

            dispatch(new AppointmentCancelledNotification($appointment, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.APPOINTMENT_CANCELLED_PATIENT_NOTIFICATION'));

            // Notifying doctor about appointment status
            $appointment->doctor->notify(new \App\Notifications\Doctor\AppointmentCancelled($appointment));
            $notification = DatabaseNotification::where(['notifiable_id' => $appointment->doctor->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Doctor\Doctor')->orderByDesc('created_at')->first();
            $unread_notifications_count = $appointment->doctor->unreadNotifications()->count();
            $total_notifications = $appointment->doctor->notifications()->count();

            // Adding condition because doctor also uses this function so adding it to prevent doctor to get his own push notification.
            if ($checkEPrescriptionAuth['authenticated_guard'] != 'doctor-api') {
                dispatch(new \App\Jobs\Doctor\AppointmentCancelledNotification($appointment, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.APPOINTMENT_CANCELLED_DOCTOR_NOTIFICATION'));
            }
        }

        if ($request['status'] == 'Checked in'){
            $appointment->doctor->notify(new PatienCheckedIn($appointment));
            $notification = DatabaseNotification::where(['notifiable_id' => $appointment->doctor->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Doctor\Doctor')->orderByDesc('created_at')->first();

            // Adding condition because doctor also uses this function so adding it to prevent doctor to get his own push notification.
            if ($checkEPrescriptionAuth['authenticated_guard'] != 'doctor-api') {
                $unread_notifications_count = $appointment->doctor->unreadNotifications()->count();
                $total_notifications = $appointment->doctor->notifications()->count();

                // Notifying doctor about patient checkedIn
                dispatch(new PatientCheckedInNotification($appointment, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.CHECKED_IN'));
            }
        }
    }

    function addDrug($request)
    {
        // $drug = Drug::;
        $drug = [
            'name' => $request['name'],
            'type' => $request['type'],
            'unit' => $request['unit'],
            'intake' =>  $request['intake'],
            'salt_name' => $request['salt_name'],
            'is_system_added' => false,
            "created_by" =>  auth()->user()->doctor_key,
            "created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now(),
        ];

        $drugQuery = Drug::create($drug);

        if ($drugQuery) {
            $drugStrengths = [];

            foreach ($request['drugStrengths'] as $key => $strength) {
                $drugStrengths[] = [
                    'drug_id' => $drugQuery->id,
                    'drug_strength' => $strength,
                    "created_at" =>  \Carbon\Carbon::now(),
                    "updated_at" => \Carbon\Carbon::now(),
                ];
            }

            $drugStrengthQuery = DrugStrength::insert($drugStrengths);

            $getDrug = Drug::where('id', $drugQuery->id)->with('drugStrength', 'manufacturer')->first();

            $response = $getDrug;
            $message = EGMBook::SUCCESS['DRUG_ADD'];
            $status = 201;
            $success = true;
        } else {
            $response = $request;
            $message = EGMBook::FAILED['DRUG_ADD'];
            $status = 400;
            $success = true;
        }

        return $this->response($request, $response, $message, $status);
    }

    public function generalMedicalProblems(){
        $medical_problems = MedicalProblem::where('is_general' , true)->get();
        $message = EGMBook::SUCCESS['GET_GENERAL_MEDICAL_PROBLEMS'];
        $status = 201;
        $success = true;
        return $this->response(null, $medical_problems, $message, $status);
    }
}
