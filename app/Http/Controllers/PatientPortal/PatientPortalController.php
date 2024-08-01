<?php

namespace App\Http\Controllers\PatientPortal;

use App\Http\Controllers\Controller;
use App\Http\Requests\EPrescription\EPrescription\AppointmentStatusChangeRequest;
use App\Http\Requests\EPrescription\Vital\GetPatientVitalsRequest;
use App\Models\EPrescription\EPrescription;
use App\Repositories\PatientPortal\Interfaces\PatientPortalRepositoryInterface;
use Illuminate\Http\Request;

class PatientPortalController extends Controller
{
    private PatientPortalRepositoryInterface $patientPortalRepository;
    public function __construct(PatientPortalRepositoryInterface $patientPortalRepository)
    {
        $this->patientPortalRepository = $patientPortalRepository;
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patientPortal/viewPatient/",
     *      operationId="viewEPrescriptionByPatientId1",
     *      tags={"Patient"},
     *      security={{"passport":{}}},
     *      summary="view-e-prescription-by-patient-id",
     *      description="view-e-prescription-by-patient-id",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function viewEPrescriptionByPatientId(){
        return $this->patientPortalRepository->viewEPrescriptionByPatientId();
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patientPortal/view/{appointment_id}/",
     *      operationId="viewPrescriptionByEPrescriptionId1",
     *      tags={"Patient"},
     *      security={{"passport":{}}},
     *      summary="view-prescription-by-e-prescription-id",
     *      description="view-prescription-by-e-prescription-id",
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
    public function viewPrescriptionByEPrescriptionId($request){
        return $this->patientPortalRepository->viewPrescriptionByEPrescriptionId($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patientPortal/patient-vitals/",
     *      operationId="getpatientvitals1",
     *      tags={"Patient"},
     *      security={{"passport":{}}},
     *      summary="get patient vitals by patient id",
     *      description="get patient vitals by patient id",
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
        return $this->patientPortalRepository->getPatientVitals($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patientPortal/generate-e-prescription-pdf/{appointment_id}/",
     *      operationId="generateEPrescription1",
     *      tags={"Patient"},
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
    public function generateEPrescription($request  , EPrescription $EPrescription){
        return $this->patientPortalRepository->generateEPrescription($request , $EPrescription);
    }

        /**
     * @OA\Post(
     *      path="/backend/api/patientPortal/change-appointment-status",
     *      operationId="changeAppointmentStatus1",
     *      tags={"Patient"},
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
     *      @OA\Parameter(
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
    public function changeAppointmentStatus(AppointmentStatusChangeRequest $request){
        return $this->patientPortalRepository->changeAppointmentStatus($request);
    }
}
