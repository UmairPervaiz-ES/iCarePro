<?php

namespace App\Traits;

use App\Helper\Helper;
use App\Models\Appointment\Appointment;
use App\Models\Patient\Patient;
use Symfony\Component\HttpFoundation\Response as StatusResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


trait CheckVEAuth
{
    /**
     * checkVitalsAuth
     *
     * @param  mixed $request
     * @return void
     */
    public function checkVitalsAuth($request){
        Auth::getDefaultDriver() == 'api' ? $requestPracticeId = auth()->guard('api')->user()->practice_id :
        (Auth::getDefaultDriver() == 'practice-api' ?  $requestPracticeId = auth()->guard('practice-api')->user()->id :
        $requestPracticeId = auth()->guard('doctor-api')->user()->practice_id);
        $patient_id = $this->identifyVitalAssociation($request['appointment_id'] ,$request['patient_id'] , $requestPracticeId);
        return [ 'practice_id' => $requestPracticeId, 'patient_id' => $patient_id ];
    }

     /**
     * Description: Verify Appointment relation with patient and practice
     * 1) Patient id return
     *
     * @param  mixed $appointment_id
     * @param  mixed $patient_id
     * @param  mixed $practice_id
     * @return void
     */
    public function identifyVitalAssociation($appointment_id ,$patient_id , $practice_id){
        // return $patient_id;
        if((!$patient_id) && ($appointment_id)){
            $patient_id = Appointment::where([['id', $appointment_id],['practice_id', $practice_id]])->first('patient_id');
            $response = @$patient_id->patient_id;
        } else if(($patient_id) && (!$appointment_id)){
            $patient_id = Patient::where('id' , $patient_id)->with( ["practicePatient" => function($q) use($practice_id , $patient_id){
                $q->where([['practice_patients.practice_id', $practice_id],['practice_patients.patient_id', $patient_id]]);
            }])->first();
            $response = @$patient_id->id;
        }
        if(!empty($response)){return $response;}
    }

    /**
     * checkEPrescriptionAuth
     *
     * @param $request
     * @return array
     */
    public function checkEPrescriptionAuth($request): array
    {
        Auth::getDefaultDriver() == 'api' ? $requestPracticeId = auth()->guard('api')->user()->practice_id :
        (Auth::getDefaultDriver() == 'practice-api' ?  $requestPracticeId = auth()->guard('practice-api')->user()->id :
        $requestPracticeId = auth()->guard('doctor-api')->user()->practice_id);
        $patient_id = null;
        if (!empty($request['appointment_id'])) {
            $patient_id = $this->findAppointment($request['appointment_id'],  $requestPracticeId);
        }
        return ['practice_id' =>  $requestPracticeId , 'patient_id' =>  $patient_id, 'authenticated_guard' => Auth::getDefaultDriver()];
    }

      /**
     * Description: Verify Appointments
     *
     * @param  mixed $appointment_id
     * @param  mixed $practice_id
     * @return void
     */
    public function findAppointment($appointment_id, $practice_id)
    {
        $patient_id = Appointment::where([['id', $appointment_id], ['practice_id', $practice_id]])->first('patient_id');
        if (!empty($patient_id)) {
            return $patient_id->patient_id;
        }
    }

}
