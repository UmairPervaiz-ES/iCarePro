<?php

namespace App\Http\Controllers\EPrescription;

use App\Http\Controllers\Controller;

class EPrescriptionSwaggerController extends Controller
{

    /**
     * @OA\Get(
     *      path="/backend/api/practice/ePrescription/generate-e-prescription-pdf/{appointment_id}/",
     *      operationId="generateEPrescriptionPractice",
     *      tags={"Practice"},
     *      security={{"passport":{}}},
     *      summary="generate EPrescription",
     *      description="generate EPrescription",
     *      @OA\Parameter(
     *      name="appointment_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
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
    public function generateEPrescription(){
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/ePrescription/change-appointment-status/",
     *      operationId="changeAppointmentStatusPractice",
     *      tags={"Practice"},
     *      security={{"passport":{}}},
     *      summary="Change Appointment Status",
     *      description="Change Appointment Status",
     *      @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Pending", "Confirmed", "Cancelled", "Completed", "Rescheduled"}
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
    public function changeAppointmentStatus(){
    }

}
