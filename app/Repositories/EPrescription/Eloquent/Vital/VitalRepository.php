<?php

namespace App\Repositories\EPrescription\Eloquent\Vital;

use App\libs\Messages\VitalGlobalMessageBook as VGMBook;
use App\Models\{Appointment\Appointment, Patient\Patient};
use App\Repositories\EPrescription\Interfaces\Vital\VitalRepositoryInterface;
use App\Traits\{CheckVEAuth, CreateOrUpdate, IdentifyCreateOrUpdate, RespondsWithHttpStatus};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class VitalRepository implements VitalRepositoryInterface
{
    use RespondsWithHttpStatus;
    use CreateOrUpdate;
    use IdentifyCreateOrUpdate;
    use CheckVEAuth;

    protected $request;
    protected $key;

    public function __construct(Request $request) {
        $this->key = ['id' => $request->id];
        $this->request = request()->all();
    }

     /**
     * Description: Create/Update Blood Pressure Vital
     * 1) If Appointment doesn't exist. It will return error
     * 2) If Blood Pressure Vital exist. It will be updated
     * 3) Activity is logged
     * 4) Blood Pressure Vital and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setBloodPressureVital($request){
        $checkVitalsAuth = $this->checkVitalsAuth($request);
        if(!$checkVitalsAuth['patient_id']){
            $response = false; $message = VGMBook::FAILED['APPOINTMENT_NOT_FOUND']; $status = 400; $success = false;
        } else{
            $data = [
                'patient_id' => $checkVitalsAuth['patient_id'],
                'practice_id' => $checkVitalsAuth['practice_id'],
                'appointment_id' => $request['appointment_id'],
                'systole' => $request['systole'],
                'diastole' => $request['diastole'],
                'type' =>  $request['type'],
                'site' =>  $request['site'],
                'cuffsize' => $request['cuffsize'],
                'not_performed' => $request['not_performed'],
                'reason' => $request['reason'],
            ];
            $response = $this->createOrUpdate('Vital\BloodPressureVital', $data,  $this->key);
            $message = VGMBook::SUCCESS['BLOOD_PRESSURE_VITAL_SET']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Create/Update Height Vital
     * 1) If Appointment doesn't exist. It will return error
     * 2) If Height Vital exist. It will be updated
     * 3) Activity is logged
     * 4) Height Vital and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setHeightVital($request){
        $checkVitalsAuth = $this->checkVitalsAuth($request);
        if(!$checkVitalsAuth['patient_id']){
            $response = false; $message = VGMBook::FAILED['APPOINTMENT_NOT_FOUND']; $status = 400; $success = false;
        } else{
            $data = [ 
                'patient_id' => $checkVitalsAuth['patient_id'],
                'practice_id' => $checkVitalsAuth['practice_id'],
                'appointment_id' => $request['appointment_id'],
                'height_inches' => $request['height_inches'],
                'type' =>  $request['type'],
                'not_performed' => $request['not_performed'],
                'reason' => $request['reason'],
            ];
            $response = $this->createOrUpdate('Vital\HeightVital', $data, $this->key);
            $message = VGMBook::SUCCESS['HEIGHT_VITAL_SET']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Create/Update Weight Vital
     * 1) If Appointment doesn't exist. It will return error
     * 2) If Weight Vital exist. It will be updated
     * 3) Activity is logged
     * 4) Weight Vital and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setWeightVital($request){
        $checkVitalsAuth = $this->checkVitalsAuth($request);
        if(!$checkVitalsAuth['patient_id']){
            $response = false; $message = VGMBook::FAILED['APPOINTMENT_NOT_FOUND']; $status = 400; $success = false;
        } else {
            $data = [
                'patient_id' => $checkVitalsAuth['patient_id'],
                'practice_id' => $checkVitalsAuth['practice_id'],
                'appointment_id' => $request['appointment_id'],
                'weight_lbs' => $request['weight_lbs'],
                'type' => $request['type'],
                'weight_prepost' => $request['weight_prepost'],
                'out_of_range' => $request['out_of_range'],
                'not_performed' => $request['not_performed'],
                'reason' => $request['reason'],
            ];
            $response = $this->createOrUpdate('Vital\WeightVital', $data, $this->key);
            $message = VGMBook::SUCCESS['WEIGHT_VITAL_SET']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Create/Update Heart Rate Vital
     * 1) If Appointment doesn't exist. It will return error
     * 2) If Heart Rate Vital exist. It will be updated
     * 3) Activity is logged
     * 4) Heart Rate Vital and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setHeartRateVital($request){
        $checkVitalsAuth = $this->checkVitalsAuth($request);
        if(!$checkVitalsAuth['patient_id']){
            $response = false; $message = VGMBook::FAILED['APPOINTMENT_NOT_FOUND']; $status = 400; $success = false;
        } else {
            $data = [
                'patient_id' => $checkVitalsAuth['patient_id'],
                'practice_id' => $checkVitalsAuth['practice_id'],
                'appointment_id' => $request['appointment_id'],
                'rate' => $request['rate'],
                'not_performed' => $request['not_performed'],
                'reason' => $request['reason'],
            ];
            $response = $this->createOrUpdate('Vital\HeartRateVital', $data, $this->key);
            $message = VGMBook::SUCCESS['HEART_RATE_VITAL_SET']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Create/Update Pulse Vital
     * 1) If Appointment doesn't exist. It will return error
     * 2) If Pulse Vital exist. It will be updated
     * 3) Activity is logged
     * 4) Pulse Vital and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setPulseVital($request){
        $checkVitalsAuth = $this->checkVitalsAuth($request);
        if(!$checkVitalsAuth['patient_id']){
            $response = false; $message = VGMBook::FAILED['APPOINTMENT_NOT_FOUND']; $status = 400; $success = false;
        } else {
            $data = [
                'patient_id' => $checkVitalsAuth['patient_id'],
                'practice_id' => $checkVitalsAuth['practice_id'],
                'appointment_id' => $request['appointment_id'],
                'rate' => $request['rate'],
                'type' => $request['type'],
                'not_performed' => $request['not_performed'],
                'reason' => $request['reason'],
            ];
            $response = $this->createOrUpdate('Vital\PulseVital', $data, $this->key);
            $message = VGMBook::SUCCESS['PULSE_VITAL_SET']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Create/Update Respiratory Rate Vital
     * 1) If Appointment doesn't exist. It will return error
     * 2) If Respiratory Rate Vital exist. It will be updated
     * 3) Activity is logged
     * 4) Respiratory Rate Vital and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setRespiratoryRateVital($request){
        $checkVitalsAuth = $this->checkVitalsAuth($request);
        if(!$checkVitalsAuth['patient_id']){
            $response = false; $message = VGMBook::FAILED['APPOINTMENT_NOT_FOUND']; $status = 400; $success = false;
        } else {
            $data = [
                'patient_id' => $checkVitalsAuth['patient_id'],
                'practice_id' => $checkVitalsAuth['practice_id'],
                'appointment_id' => $request['appointment_id'],
                'rate' => $request['rate'],
                'not_performed' => $request['not_performed'],
                'reason' => $request['reason'],
            ];
            $response = $this->createOrUpdate('Vital\RespiratoryRateVital', $data, $this->key);
            $message = VGMBook::SUCCESS['RESPIRATORY_RATE_VITAL_SET']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Create/Update Temperature Vital
     * 1) If Appointment doesn't exist. It will return error
     * 2) If Temperature Vital exist. It will be updated
     * 3) Activity is logged
     * 4) Temperature Vital and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setTemperatureVital($request){
        $checkVitalsAuth = $this->checkVitalsAuth($request);
        if(!$checkVitalsAuth['patient_id']){
            $response = false; $message = VGMBook::FAILED['APPOINTMENT_NOT_FOUND']; $status = 400; $success = false;
        } else {
            $data = [
                'patient_id' => $checkVitalsAuth['patient_id'],
                'practice_id' => $checkVitalsAuth['practice_id'],
                'appointment_id' => $request['appointment_id'],
                'temperature_f' => $request['temperature_f'],
                'examine_location' => $request['examine_location'],
                'not_performed' => $request['not_performed'],
                'reason' => $request['reason'],
            ];
            $response = $this->createOrUpdate('Vital\TemperatureVital', $data, $this->key);
            $message = VGMBook::SUCCESS['TEMPERATURE_VITAL_SET']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Create/Update BMI Vital
     * 1) If Appointment doesn't exist. It will return error
     * 2) If BMI Vital exist. It will be updated
     * 3) Activity is logged
     * 4) BMI Vital and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setBmiVital($request){
        $checkVitalsAuth = $this->checkVitalsAuth($request);
        if(!$checkVitalsAuth['patient_id']){
            $response = false; $message = VGMBook::FAILED['APPOINTMENT_NOT_FOUND']; $status = 400; $success = false;
        } else {
            $data = [
                'patient_id' => $checkVitalsAuth['patient_id'],
                'practice_id' => $checkVitalsAuth['practice_id'],
                'appointment_id' => $request['appointment_id'],
                'bmi_vital' => $request['bmi_vital'],
            ];
            $response = $this->createOrUpdate('Vital\BmiVital', $data, $this->key);
            $message = VGMBook::SUCCESS['VITAL_BMI_SET']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Create/Update WC Vital
     * 1) If Appointment doesn't exist. It will return error
     * 2) If WC Vital exist. It will be updated
     * 3) Activity is logged
     * 4) WC Vital and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setWcVital($request){ 
        $checkVitalsAuth = $this->checkVitalsAuth($request);
        if(!$checkVitalsAuth['patient_id']){
            $response = false; $message = VGMBook::FAILED['APPOINTMENT_NOT_FOUND']; $status = 400; $success = false;
        } else {
            $data = [
                'patient_id' => $checkVitalsAuth['patient_id'],
                'practice_id' => $checkVitalsAuth['practice_id'],
                'appointment_id' => $request['appointment_id'],
                'wc_vital_inches' => $request['wc_vital_inches'],
            ];
            $response = $this->createOrUpdate('Vital\WcVital', $data, $this->key);
            $message = VGMBook::SUCCESS['VITAL_WC_SET']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Create/Update Inhaled O2 Vital
     * 1) If Appointment doesn't exist. It will return error
     * 2) If Inhaled O2 Vital exist. It will be updated
     * 3) Activity is logged
     * 4) Inhaled O2 Vital and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setInhaledO2Vital($request){
        $checkVitalsAuth = $this->checkVitalsAuth($request);
        if(!$checkVitalsAuth['patient_id']){
            $response = false; $message = VGMBook::FAILED['APPOINTMENT_NOT_FOUND']; $status = 400; $success = false;
        } else{
            $data = [
                'patient_id' => $checkVitalsAuth['patient_id'],
                'practice_id' => $checkVitalsAuth['practice_id'],
                'appointment_id' => $request['appointment_id'],
                'inhaled_o2_concentration_vital' => $request['inhaled_o2_concentration_vital'],
            ];
            $response = $this->createOrUpdate('Vital\InhaledO2Vital', $data, $this->key);
            $message = VGMBook::SUCCESS['VITAL_INHALED_O2_SET']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Create/Update Pain Scale Vital
     * 1) If Appointment doesn't exist. It will return error
     * 2) If Pain Scale Vital exist. It will be updated
     * 3) Activity is logged
     * 4) Pain Scale Vital and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function setPainScaleVital($request){
        $checkVitalsAuth = $this->checkVitalsAuth($request);
        if(!$checkVitalsAuth['patient_id']){
            $response = false; $message = VGMBook::FAILED['APPOINTMENT_NOT_FOUND']; $status = 400; $success = false;
        } else {
            $data = [
                'patient_id' => $checkVitalsAuth['patient_id'],
                'practice_id' => $checkVitalsAuth['practice_id'],
                'appointment_id' => $request['appointment_id'],
                'pain_scale_vital' => $request['pain_scale_vital'],
                'notes' => $request['notes'],
            ];
            $response = $this->createOrUpdate('Vital\PainScaleVital', $data, $this->key);
            $message = VGMBook::SUCCESS['VITAL_PAIN_SCALE_SET']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
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
    public function getPatientVitals($request){
        $checkVitalsAuth = $this->checkVitalsAuth($request);

        if(empty($request['to_range']) || empty($request['from_range']) ){
            $from_range = Carbon::now()->subDays(30)->format('Y-m-d H:i:s'); $to_range = Carbon::now()->format('Y-m-d H:i:s');
        } else{
            $from_range = Carbon::parse($request['from_range'])->format('Y-m-d H:i:s'); $to_range = Carbon::parse($request['to_range'])->addDays(1)->format('Y-m-d H:i:s');
        }

        $appointment_id = $request['appointment_id'];
        if(!$checkVitalsAuth['patient_id']){
            $return = $this->response(request()->all(), false, VGMBook::FAILED['APPOINTMENT_NOT_FOUND'] ,400, false);
        } else {
            $withAppointmentRelation = [
                "bloodPressureVital" => function($q) use($appointment_id) { $q->where('blood_pressure_vitals.appointment_id', '=' , $appointment_id)->orderBy('created_at' , 'DESC');},
                "heightVital" => function($q) use($appointment_id){$q->where('height_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
                "weightVital" => function($q) use($appointment_id){$q->where('weight_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
                "heartRateVital" => function($q) use($appointment_id){$q->where('heart_rate_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
                "pulseVital" => function($q) use($appointment_id){$q->where('pulse_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
                "respiratoryRateVital" => function($q) use($appointment_id){$q->where('respiratory_rate_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
                "temperatureVital" => function($q) use($appointment_id){$q->where('temperature_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
                "painScaleVital" => function($q) use($appointment_id){$q->where('pain_scale_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
                "inhaledO2Vital" => function($q) use($appointment_id){$q->where('inhaled_o2_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
                "wcVital" => function($q) use($appointment_id){$q->where('wc_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
                "bmiVital" => function($q) use($appointment_id){$q->where('bmi_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');}
            ];
            $withoutAppointmentRelation = [
                'bloodPressureVital'   => function($q) {$q->orderBy('created_at' , 'DESC');},
                'heightVital'  => function($q) {$q->orderBy('created_at' , 'DESC');},
                'weightVital'  => function($q) {$q->orderBy('created_at' , 'DESC');},
                'heartRateVital'  => function($q) {$q->orderBy('created_at' , 'DESC');},
                'pulseVital'  => function($q) {$q->orderBy('created_at' , 'DESC');},
                'respiratoryRateVital'  => function($q) {$q->orderBy('created_at' , 'DESC');},
                'temperatureVital'  => function($q) {$q->orderBy('created_at' , 'DESC');},
                'painScaleVital'  => function($q) {$q->orderBy('created_at' , 'DESC');},
                'inhaledO2Vital'  => function($q) {$q->orderBy('created_at' , 'DESC');},
                'wcVital'  => function($q) {$q->orderBy('created_at' , 'DESC');},
                'bmiVital'  => function($q) {$q->orderBy('created_at' , 'DESC');}
            ];
    
            // $response = Patient::whereBetween('created_at', [$from_range ,  $to_range])
            // ->where('id', $checkVitalsAuth['patient_id'])
            // ->with(
            //     (!empty($appointment_id)) ? $withAppointmentRelation :  $withoutAppointmentRelation
            // )->orderBy('created_at' , 'DESC')->get();
            $response = Patient::with(
                (!empty($appointment_id)) ? $withAppointmentRelation :  $withoutAppointmentRelation
            )->where('id', $checkVitalsAuth['patient_id'])->get();
            $return = $this->response($request, $response, VGMBook::SUCCESS['GET_VITAL'], 200, true);
        }
        return  $return;
    }

   
}
