<?php

namespace App\Http\Controllers\EPrescription;

use App\Helper\Appointment;
use App\Http\Controllers\Controller;
use App\Repositories\EPrescription\Interfaces\EPrescription\EPrescriptionRepositoryInterface;
use App\Http\Requests\EPrescription\EPrescription\{AddDrugRequest, AppointmentStatusChangeRequest, SetDrugToPrescriptionRequest, SetLabTestToPrescriptionRequest,
    SetNotesPrescriptionRequest, SetPracticeLabTestsRequest, SetPracticeProceduresRequest,
    SetProcedureToPrescriptionRequest};
use App\Models\Appointment\Appointment as AppointmentAppointment;
use App\Models\EPrescription\EPrescription;
use App\Models\EPrescription\TemplateData;
use App\Models\Patient\MedicalProblem;
use App\Models\Patient\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EPrescriptionController extends Controller
{
    //
    private EPrescriptionRepositoryInterface $ePrescriptionRepository;
    public function __construct(EPrescriptionRepositoryInterface $ePrescriptionRepository)
    {
        $this->ePrescriptionRepository = $ePrescriptionRepository;
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/ePrescription/set-drug/",
     *      operationId="setDrugToPrescription",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set-drug-to-prescription",
     *      description="set-drug-to-prescription",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="medical_problem_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="drug_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *     @OA\Parameter(
     *      name="drug_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="strength_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="strength_value",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="quantity",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="mg_tab",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={ "mg","tablet(s)"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="repetition",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"every day","twice a day", "3 times a day", "4 times a day", "5 times a day", "6 times a day", "every other day", "every hour", "every 2 hours", "every 3 hours", "every 3-4 hours", "every 4 hours", "every 4-6 hours",  "every 6 hours", "every 6-8 hours", "every 8 hours", "every 12 hours", "every 24 hours", "every 72 hours",  "every week", "twice a week", "3 times a week", "every 2 weeks", "every 3 weeks",  "every 4 weeks", "every month", "every 2 months", "every 3 months", "as needed",}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="route",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={ "oral","Inject","Physical"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="when",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"before meals","with meals","after meals","in the morning","at noon","in the evening","at dinner","at bedtime","around the clock","as directed","as needed"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="quantity_unit",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"tablet(s)","mg","list pack(s) of 100","bottle(s) of 100","bottle(s) of 1000"}
     *      )
     *      ),
     *    @OA\Parameter(
     *      name="for_days",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="quantity_total",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *    @OA\Parameter(
     *      name="internal_note",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="note_to_patient",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="note_to_pharmacy",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="dispense_as_written",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *           default="0"
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
    public function setDrugToPrescription(SetDrugToPrescriptionRequest $request){
        return $this->ePrescriptionRepository->setDrugToPrescription($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctor/ePrescription/remove-drug/{id}",
     *      operationId="removeDrugFromPrescription",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="remove-drug-from-prescription",
     *      description="remove-drug-from-prescription",
     *      @OA\Parameter(
     *      name="id",
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
    public function removeDrugFromPrescription($request){
        return $this->ePrescriptionRepository->removeDrugFromPrescription($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/ePrescription/set-lab-test/",
     *      operationId="setLabTestToPrescription",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set-lab-test-to-prescription",
     *      description="set-lab-test-to-prescription",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="medical_problem_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="lab_test_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="lab_test_name",
     *      in="query",
     *      required=true,
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
    public function setLabTestToPrescription(SetLabTestToPrescriptionRequest $request){
        return $this->ePrescriptionRepository->setLabTestToPrescription($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctor/ePrescription/remove-lab-test/{id}",
     *      operationId="removeLabTestFromPrescription",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="remove-lab-test-from-prescription",
     *      description="remove-lab-test-from-prescription",
     *      @OA\Parameter(
     *      name="id",
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
    public function removeLabTestFromPrescription($request){
        return $this->ePrescriptionRepository->removeLabTestFromPrescription($request);
    }

     /**
     * @OA\Post(
     *      path="/backend/api/doctor/ePrescription/set-procedure/",
     *      operationId="setProcedureToPrescription",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set-procedure-to-prescription",
     *      description="set-procedure-to-prescription",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="medical_problem_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="procedure_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="procedure_name",
     *      in="query",
     *      required=true,
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
    public function setProcedureToPrescription(SetProcedureToPrescriptionRequest $request){
        return $this->ePrescriptionRepository->setProcedureToPrescription($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctor/ePrescription/remove-procedure/{id}",
     *      operationId="removeProcedureFromPrescription",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="remove-procedure-from-prescription",
     *      description="remove-procedure-from-prescription",
     *      @OA\Parameter(
     *      name="id",
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
    public function removeProcedureFromPrescription($request){
        return $this->ePrescriptionRepository->removeProcedureFromPrescription($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/ePrescription/set-notes/",
     *      operationId="setNotesPrescription",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set-notes-prescription",
     *      description="set-notes-prescription",
     *      @OA\Parameter(
     *      name="appointment_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
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
    public function setNotesPrescription(SetNotesPrescriptionRequest $request){
        return $this->ePrescriptionRepository->setNotesPrescription($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/ePrescription/set-practice-procedures/",
     *      operationId="setPracticeProcedures",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set-practice-procedures",
     *      description="set-practice-procedures",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="description",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="price",
     *      in="query",
     *      required=true,
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
    public function setPracticeProcedures(SetPracticeProceduresRequest $request){
        return $this->ePrescriptionRepository->setPracticeProcedures($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctor/ePrescription/remove-practice-procedures/{id}",
     *      operationId="removePracticeProcedure",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="remove-practice-procedure",
     *      description="remove-practice-procedure",
     *      @OA\Parameter(
     *      name="id",
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
    public function removePracticeProcedures($request){
        return $this->ePrescriptionRepository->removePracticeProcedures($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/ePrescription/set-practice-lab-tests/",
     *      operationId="setPracticeLabTests",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set-practice-lab-tests",
     *      description="set-practice-lab-tests",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="description",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="price",
     *      in="query",
     *      required=true,
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
    public function setPracticeLabTests(SetPracticeLabTestsRequest $request){
        return $this->ePrescriptionRepository->setPracticeLabTests($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctor/ePrescription/remove-practice-lab-tests/{id}",
     *      operationId="removePracticeLabTests",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="remove-practice-lab-tests",
     *      description="remove-practice-lab-tests",
     *      @OA\Parameter(
     *      name="id",
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
    public function removePracticeLabTests($request){
        return $this->ePrescriptionRepository->removePracticeLabTests($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctor/ePrescription/viewPatient/{pId}/",
     *      operationId="viewEPrescriptionByPatientId",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="view-e-prescription-by-patient-id",
     *      description="view-e-prescription-by-patient-id",
     *      @OA\Parameter(
     *      name="pId",
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
    public function viewEPrescriptionByPatientId($request){
        return $this->ePrescriptionRepository->viewEPrescriptionByPatientId($request);
    }

     /**
     * @OA\Get(
     *      path="/backend/api/doctor/ePrescription/view/{eId}/",
     *      operationId="viewPrescriptionByEPrescriptionId",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="view-prescription-by-e-prescription-id",
     *      description="view-prescription-by-e-prescription-id",
     *      @OA\Parameter(
     *      name="eId",
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
    public function viewPrescriptionByEPrescriptionId($id){
        return $this->ePrescriptionRepository->viewPrescriptionByEPrescriptionId($id);
    }

         /**
     * @OA\Get(
     *      path="/backend/api/doctor/ePrescription/drug/{drug_id}/",
     *      operationId="getDrugByDrugId",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="get-drug-by-drug-id",
     *      description="get-drug-by-drug-id",
     *      @OA\Parameter(
     *      name="drug_id",
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
    public function getDrugByDrugId($request){
        return $this->ePrescriptionRepository->getDrugByDrugId($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctor/ePrescription/get-procedure/{query}/",
     *      operationId="getProcedureForDropdown",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="get-procedure-by-drug-id",
     *      description="get-procedure-by-drug-id",
     *      @OA\Parameter(
     *      name="query",
     *      in="path",
     *      required=true,
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
    public function getProcedureForDropdown($request){
        return $this->ePrescriptionRepository->getProcedureForDropdown($request);
    }

             /**
     * @OA\Get(
     *      path="/backend/api/doctor/ePrescription/get-lab-tests/{query}/",
     *      operationId="getLabTestsForDropdown",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="get-lab-tests-for-dropdown",
     *      description="get-lab-tests-for-dropdown",
     *      @OA\Parameter(
     *      name="query",
     *      in="path",
     *      required=true,
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
    public function getLabTestsForDropdown($request){
        return $this->ePrescriptionRepository->getLabTestsForDropdown($request);
    }

             /**
     * @OA\Get(
     *      path="/backend/api/doctor/ePrescription/get-drugs/{query}/",
     *      operationId="getDrugsForDropdown",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="get-drugs-for-dropdown",
     *      description="get-drugs-for-dropdown",
     *      @OA\Parameter(
     *      name="query",
     *      in="path",
     *      required=true,
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
    public function getDrugsForDropdown($request){
        return $this->ePrescriptionRepository->getDrugsForDropdown($request);
    }


    /**
     * @OA\Get(
     *      path="/backend/api/doctor/ePrescription/generate-e-prescription-pdf/{appointment_id}/",
     *      operationId="generateEPrescription",
     *      tags={"Doctor"},
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
    public function generateEPrescription($request){
        return $this->ePrescriptionRepository->generateEPrescription($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/ePrescription/change-appointment-status/",
     *      operationId="changeAppointmentStatus",
     *      tags={"Doctor"},
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
    public function changeAppointmentStatus(AppointmentStatusChangeRequest $request){
        return $this->ePrescriptionRepository->changeAppointmentStatus($request);
    }


    /**
     * @OA\Get(
     *      path="/backend/api/practice/ePrescription/ePrescriptionTemplateData",
     *      operationId="ePrescriptionTemplateData",
     *      tags={"Practice"},
     *      security={{"passport":{}}},
     *      summary="Get ePrescription Template data",
     *      description="Get ePrescription Template data",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function ePrescriptionTemplateData()
    {
        return $this->ePrescriptionRepository->ePrescriptionTemplateData();
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/ePrescription/set-template-data",
     *      operationId="setTemplateData",
     *      tags={"Practice"},
     *      security={{"passport":{}}},
     *      summary="set-template-data",
     *      description="set template data",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="header_data",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="footer_dta",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="color_scheme",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="file_paths[]",
     *                      type="array",
     *                      @OA\Items(
     *                          type="file",
     *                          format="binary",
     *                      ),
     *                  )
     *              )
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
    public function setEPrescriptionTemplateData(Request $request)
    {
        return $this->ePrescriptionRepository->setEPrescriptionTemplateData($request);
    }

/**
     * @OA\Post(
     *      path="/backend/api/doctor/drug/add",
     *      operationId="doctorDrug",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="set drug for practice",
     *      description="set drug for practice",
     *      @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Liquid","Tablet", "Capsule", "Plasma/Topical/Serum", "Suppositories", "Drops", "Inhalers", "Injections", "Implants and patches", "Lozenges"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="unit",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           enum={"mg", "ml", "mcg", "mg/ml", "gm", "IU/ml", "ml/L"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="intake",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Oral","Inhalation", "Injection", "Topical", "Spray"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="salt_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="drugStrengths",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="file_paths[]",
     *                      type="array",
     *                      @OA\Items(
     *                          type="file",
     *                          format="binary",
     *                      ),
     *                  )
     *              )
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
    public function addDrug(AddDrugRequest $request)
    {
        return $this->ePrescriptionRepository->addDrug($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctor/ePrescription/general-medical-problems",
     *      operationId="doctorGeneralMedicalProblems",
     *      tags={"Doctor"},
     *      security={{"passport":{}}},
     *      summary="get general medical problems",
     *      description="get general medical problems",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function generalMedicalProblems()
    {
        return $this->ePrescriptionRepository->generalMedicalProblems();
    }


    // public function viewEp(){
    //     $appointment_id = 1;
    //     $data['data'] =  EPrescription::where('appointment_id', $appointment_id)
    //     ->with('doctor', 'practice', 'practice.initialPractice', 'patient', 'prescribedDrugs.drug','prescribedLabTests','prescribedProcedures', 'prescribedProcedures.procedure', 'appointment'
    //     )->first()->toArray();

    //     $appointment = AppointmentAppointment::where('id', $appointment_id)->first();

    //     $problem_ids = array_map('intval', explode(',', $appointment->medical_problem_id));
    //     $data['data']['medical_problems'] = MedicalProblem::select('name')->whereIn('id' ,  $problem_ids)->get()->toArray();

    //     $data['data']['vitals'] = Patient::where('id', $appointment['patient_id'])
    //     ->with(
    //         [
    //             "bloodPressureVital" => function($q) use($appointment_id) { $q->where('blood_pressure_vitals.appointment_id', '=' , $appointment_id)->orderBy('created_at' , 'DESC');},
    //             "heightVital" => function($q) use($appointment_id){$q->where('height_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
    //             "weightVital" => function($q) use($appointment_id){$q->where('weight_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
    //             "heartRateVital" => function($q) use($appointment_id){$q->where('heart_rate_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
    //             "pulseVital" => function($q) use($appointment_id){$q->where('pulse_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
    //             "respiratoryRateVital" => function($q) use($appointment_id){$q->where('respiratory_rate_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
    //             "temperatureVital" => function($q) use($appointment_id){$q->where('temperature_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
    //             "painScaleVital" => function($q) use($appointment_id){$q->where('pain_scale_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
    //             "inhaledO2Vital" => function($q) use($appointment_id){$q->where('inhaled_o2_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
    //             "wcVital" => function($q) use($appointment_id){$q->where('wc_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');},
    //             "bmiVital" => function($q) use($appointment_id){$q->where('bmi_vitals.appointment_id', '=', $appointment_id)->orderBy('created_at' , 'DESC');}
    //         ]
    //     )->orderBy('created_at' , 'DESC')->first()->toArray();


    //     $data['templateData'] = TemplateData::where('practice_id', $appointment['practice_id'])->first();
    //     return view('ePrescription.ePrescriptionView', $data);
    // }
}
