<?php
namespace App\Http\Controllers\EPrescription;

use App\Http\Controllers\Controller;

class VitalSwaggerController extends Controller
{

     /**
     * @OA\Post(
     *      path="/backend/api/practice/vitals/set-blood-pressure/",
     *      operationId="setBloodPressureVitalPractice",
     *      tags={"Practice"},
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
    public function setBloodPressureVital()
    {
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/vitals/set-height/",
     *      operationId="setHeightVitalPractice",
     *      tags={"Practice"},
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
    public function setHeightVital(){
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/vitals/set-weight/",
     *      operationId="setweightvitalPractice",
     *      tags={"Practice"},
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
    public function setWeightVital(){
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/vitals/set-heart-rate/",
     *      operationId="setheartratevitalPractice",
     *      tags={"Practice"},
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
    public function setHeartRateVital(){
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/vitals/set-pulse/",
     *      operationId="setpulsevitalPractice",
     *      tags={"Practice"},
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
    public function setPulseVital(){
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/vitals/set-respiratory-rate/",
     *      operationId="setrespiratoryratevitalPractice",
     *      tags={"Practice"},
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
    public function setRespiratoryRateVital(){
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/vitals/set-temperature/",
     *      operationId="settemperaturevitalPractice",
     *      tags={"Practice"},
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
    public function setTemperatureVital(){
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/vitals/set-bmi/",
     *      operationId="setBmivitalPractice",
     *      tags={"Practice"},
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
    public function setBmiVital(){
    }


        /**
     * @OA\Post(
     *      path="/backend/api/practice/vitals/set-wc/",
     *      operationId="setWcVitalPractice",
     *      tags={"Practice"},
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
    public function setWcVital(){
    }



        /**
     * @OA\Post(
     *      path="/backend/api/practice/vitals/set-inhaled-o2/",
     *      operationId="setInhaledO2VitalPractice",
     *      tags={"Practice"},
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
    public function setInhaledO2Vital(){
    }



    /**
     * @OA\Post(
     *      path="/backend/api/practice/vitals/set-pain-scale/",
     *      operationId="setPainScaleVitalPractice",
     *      tags={"Practice"},
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
    public function setPainScaleVital(){
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/vitals/patient-vitals/",
     *      operationId="getpatientvitalsPractice",
     *      tags={"Practice"},
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
    public function getPatientVitals(){
    }
}
