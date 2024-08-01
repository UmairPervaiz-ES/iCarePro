<?php

namespace App\Repositories\PatientPortal\Eloquent;

use App\Jobs\Doctor\AppointmentCancelledNotification;
use App\Models\EPrescription\EPrescription;
use App\Notifications\Doctor\AppointmentCancelled;
use App\Repositories\PatientPortal\Interfaces\PatientPortalRepositoryInterface;
use App\libs\Messages\VitalGlobalMessageBook as VGMBook;
use App\libs\Messages\EPrescriptionGlobalMessageBook as EGMBook;
use App\Models\Patient\Patient;
use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;
use Validator;
use App\Models\{
    Appointment\Appointment
};

use App\Traits\RespondsWithHttpStatus;
use App\Jobs\Appointment\DoctorAppointment as AppointmentDoctorAppointment;
use App\Jobs\Appointment\PatientAppointment as AppointmentPatientAppointment;
use App\Models\Doctor\Doctor;
use App\Models\EPrescription\TemplateData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use PDF;

class PatientPortalRepository implements PatientPortalRepositoryInterface
{
    use RespondsWithHttpStatus;

    /**
     * Description: View e-prescriptions by patient id
     * 1) E-prescriptions with pagination return
     *
     * @param  mixed $request
     * @return void
     */
    public function viewEPrescriptionByPatientId()
    {
        $prescription  =  EPrescription::where('patient_id', auth()->guard('patient-api')->user()->id)->paginate('15');
        return $this->response(auth()->guard('patient-api')->user()->id, $prescription, EGMBooK::SUCCESS['E_PRESCRIPTION_PID_FETCHED'], 200, true);
    }

    /**
     * Description: View e-prescriptions by e-prescription id
     * 1) E-prescription return
     *
     * @param  mixed $request
     * @return void
     */
    public function viewPrescriptionByEPrescriptionId($request) {
        $prescription = EPrescription::where('appointment_id' , $request)->with('prescribedDrugs.drug', 'prescribedDrugs.drugStrength' , 'prescribedDrugs.allDrugStrength',
            'prescribedLabTests', 'prescribedProcedures')->first();
        return $this->response($request, $prescription, EGMBook::SUCCESS['E_PRESCRIPTION_EID_FETCHED'], 200, true);
    }

    /**
     * Description: Get Patient Vitals
     * 1) If Appointment doesn't exist. It will return error
     * 2) If Date Range Empty. It will return last 30 days data
     * 3) If Date Range not Empty. It will return data between range
     * 4) Vitals return
     *
     * @param  mixed $request
     * @return void
     */
    public function getPatientVitals($request)
    {
        if (empty($request['to_range']) || empty($request['from_range'])) {
            $from_range = Carbon::now()->subDays(30)->format('Y-m-d H:i:s');
            $to_range = Carbon::now()->format('Y-m-d H:i:s');
        } else {
            $from_range = Carbon::parse($request['from_range'])->format('Y-m-d H:i:s');
            $to_range = Carbon::parse($request['to_range'])->addDays(1)->format('Y-m-d H:i:s');
        }

        $appointment_id = $request['appointment_id'];
        $patient_id = auth()->guard('patient-api')->user()->id;
        if (!$patient_id) {
            $return = $this->response(request()->all(), false, VGMBook::FAILED['APPOINTMENT_NOT_FOUND'], 400, false);
        } else {
            $withAppointmentRelation = [
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
            ];
            $withoutAppointmentRelation = [
                'bloodPressureVital'   => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                },
                'heightVital'  => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                },
                'weightVital'  => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                },
                'heartRateVital'  => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                },
                'pulseVital'  => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                },
                'respiratoryRateVital'  => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                },
                'temperatureVital'  => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                },
                'painScaleVital'  => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                },
                'inhaledO2Vital'  => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                },
                'wcVital'  => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                },
                'bmiVital'  => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ];

            $response = Patient::whereBetween('created_at', [$from_range,  $to_range])
                ->where('id', $patient_id)
                ->with(
                    (!empty($appointment_id)) ? $withAppointmentRelation :  $withoutAppointmentRelation
                )->orderBy('created_at', 'DESC')->get();
            $return = $this->response($request, $response, VGMBook::SUCCESS['GET_VITAL'], 200, true);
        }
        return  $return;
    }

    /**
     * Description: Generate E-Prescription PDF and Save it on Server
     * 1) Pdf Path return
     *
     * @param  mixed $request
     * @return void
     */
    public function generateEPrescription($request , $EPrescription)
    {
        $request = ['appointment_id' => $request];
        Validator::make($request, ['appointment_id' => 'numeric']);
        $patient_key = auth()->guard('patient-api')->user()->patient_key;
        $path = storage_path('app/public/e-prescriptions/' . $patient_key);
        $file = $path . '/' . $request['appointment_id'] . '.pdf';
        if (!File::exists($path)) {
            File::makeDirectory($path);
        }
        if (!File::exists($file)) {
            $appointment = Appointment::where('id', $request['appointment_id'])->first();
            if (!$EPrescription::where('appointment_id', $request['appointment_id'])->exists()) {
                $data = [
                'patient_id' => auth()->id(),
                'practice_id' => $appointment['practice_id'],
                'doctor_id' => $appointment['doctor_id'],
                'appointment_id' => $request['appointment_id'],
                ];
                $EPrescription::create($data);
            }
            $data['data'] =  $EPrescription::where('appointment_id' , $request['appointment_id'])->with('doctor','practice','practice.initialPractice','patient','prescribedDrugs.drug',
            'prescribedLabTests', 'prescribedProcedures', 'appointment')->first()->toArray();
            $data['templateData'] = TemplateData::where('practice_id', $appointment['practice_id'])->first();
            PDF::loadView('ePrescription.ePrescriptionView', $data)->save($path.'/'.$request['appointment_id'].'.pdf');
        }
        if ($patient_key) {
            $message = EGMBook::SUCCESS['PDF_CREATED'];
            $status = 200;
            $success = true;
            $response = config('constants.PRACTICE_URL').'backend/download-pdf/'.$patient_key.'/'.$request['appointment_id'];
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
     *
     * @param  mixed $request
     * @return void
     */
    public function changeAppointmentStatus($request)
    {
        $patient_id =  auth()->guard('patient-api')->user()->id;
        if ($patient_id) {
            ($request['status'] == 'Cancelled') ? ($response = Appointment::where('id', $request['appointment_id'])->update(['status' => $request['status'], 'reason' => $request['reason'], 'comments' =>  $request['comments']])) : ($response = Appointment::where('id', $request['appointment_id'])->update(['status' => $request['status']]));
            $appointment = Appointment::where('id', $request['appointment_id'])->first();
            $patient = Patient::where('id', $appointment->patient_id)->first();
            $doctor = Doctor::where('id', $appointment->doctor_id)->first();
            $practice = DB::SELECT("SELECT practice_registration_requests.practice_name , practice_addresses.address_line_1, practice_addresses.address_line_2
             FROM practices
             INNER JOIN practice_registration_requests ON practices.practice_registration_request_id = practice_registration_requests.id
             INNER JOIN practice_addresses ON practices.id = practice_addresses.practice_id
             where practices.id = '{$appointment->practice_id}';");

            if ($request['status'] == 'Cancelled')
            {
                // Notifying doctor about appointment status
                $appointment->doctor->notify(new AppointmentCancelled($appointment));
                $notification = DatabaseNotification::where(['notifiable_id' => $appointment->doctor->id, 'read_at' => null])->where('notifiable_type', 'App\Models\Doctor\Doctor')->orderByDesc('created_at')->first();
                $unread_notifications_count = $appointment->doctor->unreadNotifications()->count();
                $total_notifications = $appointment->doctor->notifications()->count();

                dispatch(new AppointmentCancelledNotification($appointment, $notification, $unread_notifications_count, $total_notifications))->onQueue(config('constants.APPOINTMENT_CANCELLED_DOCTOR_NOTIFICATION'));
            }

            // send email doctor and patient
            dispatch(new AppointmentPatientAppointment($appointment, $doctor, $patient, $practice , null))->onQueue(config('constants.PATIENT_APPOINTMENT'));
            dispatch(new AppointmentDoctorAppointment($appointment, $doctor, $patient, $practice , null))->onQueue(config('constants.DOCTOR_APPOINTMENT'));
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
}
