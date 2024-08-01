<?php
namespace App\Http\Controllers\EPrescription;

use App\Http\Controllers\Controller;
use App\Repositories\EPrescription\Interfaces\Vital\VitalRepositoryInterface;
use App\Http\Requests\EPrescription\Vital\{SetBloodPressureVitalRequest, SetHeightVitalRequest, SetWeightVitalRequest,
    SetHeartRateVitalRequest, SetTemperatureVitalRequest, SetPainScaleVitalsRequest, SetPulseVitalRequest,
    SetRespiratoryRateVitalRequest, GetPatientVitalsRequest, SetInhaledO2VitalsRequest, SetwcVitalsRequest,
    SetBmiVitalsRequest};


class VitalController extends Controller
{
    //
    private VitalRepositoryInterface $vitalRepository;
    public function __construct(VitalRepositoryInterface $vitalRepository)
    {
        $this->vitalRepository = $vitalRepository;
    }

     /**
     * @OA\Post(
     *      path="/backend/api/doctor/vitals/set-blood-pressure/",
     *      operationId="setBloodPressureVital",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="Set blood pressure vital",
     *      description="Set blood pressure vital",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="systole",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="diastole",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"sitting","standing", "supine","lying on side","prone"}
     *          )
     *      ),
     *     @OA\Parameter(
     *      name="site",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"L arm", "R arm", "L leg", "R leg","L wrist","R wrist"}
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="cuffsize",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"neonatal","infant", "small pediatric","pediatric","small adult","adult","large adult","child thigh","adult thigh"}
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="not_performed",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer",
     *           default= "0"
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="reason",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Not indicated","Not tolerated","Patient refused"}
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setBloodPressureVital(SetBloodPressureVitalRequest $request)
    {
        return $this->vitalRepository->setBloodPressureVital($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/vitals/set-height/",
     *      operationId="setHeightVital",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set height vital",
     *      description="set height vital",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="height_inches",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Stated","Lying","Standing","Preoperative"}
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="not_performed",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer",
     *           default="0"
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="reason",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Not indicated","Not tolerated","Patient refused"}
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setHeightVital(SetHeightVitalRequest $request){
        return $this->vitalRepository->setHeightVital($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/vitals/set-weight/",
     *      operationId="setweightvital",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set weight vital",
     *      description="set weight vital",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="weight_lbs",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Stated","Dry","Preoperative","With clothes","Without clothes","First",}
     *          )
     *      ),
     *     @OA\Parameter(
     *      name="weight_prepost",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Pre-dialysis","Post-dialysis",}
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="out_of_range",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer",
     *           default="0"
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="not_performed",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer",
     *           default="0"
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="reason",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Not indicated","Not tolerated","Patient refused"}
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setWeightVital(SetWeightVitalRequest $request){
        return $this->vitalRepository->setWeightVital($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/vitals/set-heart-rate/",
     *      operationId="setheartratevital",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set heart rate vital",
     *      description="set heart rate vital",
     *     @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *         @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="rate",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="not_performed",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer",
     *           default="0"
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="reason",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Not indicated","Not tolerated","Patient refused"}
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setHeartRateVital(SetHeartRateVitalRequest $request){
        return $this->vitalRepository->setHeartRateVital($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/vitals/set-pulse/",
     *      operationId="setpulsevital",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set pulse vital",
     *      description="set pulse vital",
     *     @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="rate",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={ "regular","irregular","regularly irregular","irregularly irregular"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="not_performed",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer",
     *           default="0"
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="reason",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Not indicated","Not tolerated","Patient refused"}
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPulseVital(SetPulseVitalRequest $request){
        return $this->vitalRepository->setPulseVital($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/vitals/set-respiratory-rate/",
     *      operationId="setrespiratoryratevital",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set respiratory rate vital",
     *      description="set respiratory rate vital",
     *     @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="rate",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="not_performed",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer",
     *           default="0"
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="reason",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Not indicated","Not tolerated","Patient refused"}
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setRespiratoryRateVital(SetRespiratoryRateVitalRequest $request){
        return $this->vitalRepository->setRespiratoryRateVital($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/vitals/set-temperature/",
     *      operationId="settemperaturevital",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set temperature vital",
     *      description="set temperature vital",
     *     @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *    @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="temperature_f",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="examine_location",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"oral", "ear", "axillary",  "rectal", "temporal artery"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="not_performed",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer",
     *           default="0"
     *          )
     *      ),
     *      @OA\Parameter(
     *      name="reason",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Not indicated","Not tolerated","Patient refused"}
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setTemperatureVital(SetTemperatureVitalRequest $request){
        return $this->vitalRepository->setTemperatureVital($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/vitals/set-bmi/",
     *      operationId="setBmivital",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set bmi vitals",
     *      description="set bmi vitals",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="bmi_vital",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setBmiVital(SetBmiVitalsRequest $request){
        return $this->vitalRepository->setBmiVital($request);
    }


        /**
     * @OA\Post(
     *      path="/backend/api/doctor/vitals/set-wc/",
     *      operationId="setWcVital",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set wc vitals",
     *      description="set wc vitals",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="wc_vital_inches",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setWcVital(SetwcVitalsRequest $request){
        return $this->vitalRepository->setWcVital($request);
    }



        /**
     * @OA\Post(
     *      path="/backend/api/doctor/vitals/set-inhaled-o2/",
     *      operationId="setInhaledO2Vital",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set o2 vitals",
     *      description="set o2 vitals",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="inhaled_o2_concentration_vital",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setInhaledO2Vital(SetInhaledO2VitalsRequest $request){
        return $this->vitalRepository->setInhaledO2Vital($request);
    }



    /**
     * @OA\Post(
     *      path="/backend/api/doctor/vitals/set-pain-scale/",
     *      operationId="setPainScaleVital",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set pain scale vitals",
     *      description="set pain scale vitals",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="pain_scale_vital",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="notes",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPainScaleVital(SetPainScaleVitalsRequest $request){
        return $this->vitalRepository->setPainScaleVital($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/vitals/patient-vitals/",
     *      operationId="getpatientvitals",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="get patient vitals by patient id",
     *      description="get patient vitals by patient id",
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="from_range",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="to_range",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getPatientVitals(GetPatientVitalsRequest $request){
        return $this->vitalRepository->getPatientVitals($request);
    }
}
