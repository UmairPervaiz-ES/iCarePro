<?php

namespace App\Http\Controllers\Patient\PatientHistory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\PatientHistory\PatientAllergyRequest;
use App\Repositories\Patient\Interfaces\PatientHistory\PatientHistoryRepositoryInterface;
use App\Http\Requests\Patient\PatientHistory\PatientCommonContactRequest;
use App\Http\Requests\Patient\PatientHistory\PatientContactRequest;
use App\Http\Requests\Patient\PatientHistory\PatientDemographyRequest;
use App\Http\Requests\Patient\PatientHistory\PatientEmploymentRequest;
use App\Http\Requests\Patient\PatientHistory\PatientFamilyHistoryRequest;
use App\Http\Requests\Patient\PatientHistory\PatientIdentificationRequest;
use App\Http\Requests\Patient\PatientHistory\PatientMedicalProblemRequest;
use App\Http\Requests\Patient\PatientHistory\PatientPrivacyRequest;
use App\Http\Requests\Patient\PatientHistory\PatientReferenceGetRequest;
use App\Http\Requests\Patient\PatientHistory\PatientSocialHistoryRequest;
use App\Http\Requests\Patient\PatientHistory\PatientSurgicalHistoryRequest;
use App\Http\Requests\Patient\PatientHistory\PatientVaccineRequest;
use Illuminate\Http\Request;

class PatientHistoryController extends Controller
{
    private PatientHistoryRepositoryInterface $patientHistoryRepository;
    public function __construct(PatientHistoryRepositoryInterface $patientHistoryRepository)
    {
        $this->patientHistoryRepository = $patientHistoryRepository;
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/reference-contacts/{patient_id}",
     *      operationId="getReferenceContacts",
     *      tags={"Patient"},
     *      summary="Patient reference contacts",
     *      description="Patient reference contacts list ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function allReferenceContact($patient_id)
    {
        return $this->patientHistoryRepository->allReferenceContact($patient_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/set-reference-contact",
     *      operationId="addReferenceContact",
     *      tags={"Patient"},
     *      summary="Add Reference Contact ",
     *      description="Add patient reference contact like next to kin guardian emergency contact",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="contact_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="patient_relationship",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="middle_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="last_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="suffix",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *          enum={"Miss","Mrs","Ms","Mr"}
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="dob",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="address",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="zip_code",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="country_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="city_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="state_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="emirates_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="country_code",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="phone",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="contact_reference",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           enum={"guarantor", "guardian", "next to kin", "emergency contact"}
     *      )
     *     ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPatientReferenceContact(PatientCommonContactRequest $request)
    {
        return $this->patientHistoryRepository->setPatientReferenceContact($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/delete-reference-contact/{contact_id}",
     *      operationId="deleteReferenceContact",
     *      tags={"Patient"},
     *      summary="Delete reference contacts",
     *      description="Delete patient reference contacts ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="contact_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function deletePatientReferenceContact($contact_id)
    {
        return $this->patientHistoryRepository->deletePatientReferenceContact($contact_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/set-family-history",
     *      operationId="addFamilyHistory",
     *      tags={"Patient"},
     *      summary="Add patient family history ",
     *      description=" Add patient family history ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="family_history_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="medical_problem_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="patient_relationship_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="onset_age",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="died",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="note",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\MediaType(
     *       mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPatientFamilyHistory(PatientFamilyHistoryRequest $request)
    {
        return $this->patientHistoryRepository->setPatientFamilyHistory($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/family-history/{patient_id}",
     *      operationId="listFamilyHistory",
     *      tags={"Patient"},
     *      summary=" List family history",
     *      description="Patient family history list",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function allFamilyHistory($patient_id)
    {
        return $this->patientHistoryRepository->allFamilyHistory($patient_id);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/delete-family-history/{history_id}",
     *      operationId="deleteFamilyHistory",
     *      tags={"Patient"},
     *      summary="Delete family history",
     *      description="Delete patient family history",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="history_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function deleteFamilyHistory($history_id)
    {
        return $this->patientHistoryRepository->deleteFamilyHistory($history_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/set-medical-problem",
     *      operationId="addMedicalProblem",
     *      tags={"Patient"},
     *      summary="Add Medical Problem ",
     *      description="Add patient medical problem with proper details medical problem time ,duration  ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_medical_problem_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="medical_problem_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Active", "Historical"}
     *
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="removal_reason",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Chronic", "Acute"}
     *
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="onset_date",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="last_occurrence",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="note",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\MediaType(
     *       mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPatientMedicalProblem(PatientMedicalProblemRequest $request)
    {
        return $this->patientHistoryRepository->setPatientMedicalProblem($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/patient-medical-problem/{patient_id}",
     *      operationId="listPatientMedicalProblem",
     *      tags={"Patient"},
     *      summary="List patient medical problem",
     *      description="List patient medical problem ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function allPatientMedicalProblem($patient_id)
    {
        return $this->patientHistoryRepository->allPatientMedicalProblem($patient_id);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/delete-patient-medical-problem/{problem_id}",
     *      operationId="deletePatientMedicalProblem",
     *      tags={"Patient"},
     *      summary="Delete Patient Medical Problem",
     *      description="Delete Patient Medical Problem ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="problem_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function deletePatientMedicalProblem($problem_id)
    {
        return $this->patientHistoryRepository->deletePatientMedicalProblem($problem_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/set-patient-surgery-history",
     *      operationId="addPatientSurgeryHistory",
     *      tags={"Patient"},
     *      summary="Add surgical ",
     *      description=" Add patient surgical history  ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_surgery_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="surgery_procedure_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="date",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="note",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\MediaType(
     *       mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPatientSurgicalHistory(PatientSurgicalHistoryRequest $request)
    {
        return $this->patientHistoryRepository->setPatientSurgicalHistory($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/patient-surgery-history/{patient_id}",
     *      operationId="listPatientSurgeryHistory",
     *      tags={"Patient"},
     *      summary="List patient surgery history",
     *      description="List patient surgery history ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function allPatientSurgicalHistory($patient_id)
    {
        return $this->patientHistoryRepository->allPatientSurgicalHistory($patient_id);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/delete-patient-surgery-history/{surgery_id}",
     *      operationId="deletePatientSurgeryHistory",
     *      tags={"Patient"},
     *      summary="Delete patient surgery history",
     *      description="Delete patient surgery history",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="surgery_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function deletePatientSurgicalHistory($surgery_id)
    {
        return $this->patientHistoryRepository->deletePatientSurgicalHistory($surgery_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/vaccine",
     *      operationId="listAllVaccine",
     *      tags={"Patient"},
     *      summary="All Vaccine",
     *      description="List all vaccine",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="search",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function allVaccine(Request $request)
    {
        return $this->patientHistoryRepository->allVaccine($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/surgery",
     *      operationId="listAllSurgery",
     *      tags={"Patient"},
     *      summary="All Surgery",
     *      description="List all surgery",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="search",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function allSurgery(Request $request)
    {
        return $this->patientHistoryRepository->allSurgery($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/set-patient-vaccine",
     *      operationId="addVaccine",
     *      tags={"Patient"},
     *      summary="Add patient vaccine ",
     *      description="Add patient vaccine with proper details like type , manufacture and amount etc",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_vaccine_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="vaccine_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="route_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="national_drug_code_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="site_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="manufacture_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="administer_date",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="administer_by",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="amount",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="unit",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *        enum={"mcg", "ml", "mg"}
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="lot_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="expiry_date",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="vaccine_given_date",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="date_on_vaccine",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\MediaType(
     *       mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPatientVaccine(PatientVaccineRequest $request)
    {
        return $this->patientHistoryRepository->setPatientVaccine($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/patient-vaccine/{patient_id}",
     *      operationId="listPatientVaccine",
     *      tags={"Patient"},
     *      summary="Patient Vaccine",
     *      description="c ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function allPatientVaccine($patient_id)
    {
        return $this->patientHistoryRepository->allPatientVaccine($patient_id);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/delete-patient-vaccine/{patient_vaccine_id}",
     *      operationId="deletePatientVaccine",
     *      tags={"Patient"},
     *      summary="Delete Patient Vaccine",
     *      description="All vaccine lish delete relate to patient",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_vaccine_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function deletePatientVaccine($patient_vaccine_id)
    {
        return $this->patientHistoryRepository->deletePatientVaccine($patient_vaccine_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/medical-problem",
     *      operationId="medicalProblem",
     *      tags={"Patient"},
     *      summary="Medical Problem",
     *      description="All Medical Problem ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="search",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function allMedicalProblem(Request $request)
    {
        return $this->patientHistoryRepository->allMedicalProblem($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/relationship",
     *      operationId="allRelationship",
     *      tags={"Patient"},
     *      summary="All relationship list",
     *      description="All relationship",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function allRelationship()
    {
        return $this->patientHistoryRepository->allRelationship();
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/set-patient-social-history",
     *      operationId="addPatientSocialHistory",
     *      tags={"Patient"},
     *      summary="Add patient social history ",
     *      description="Add and edit patient social history ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_social_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="gender_identity",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Identifies as Male", "Identifies as Female", "Transgender Male/Female-to-Male (FTM)", "Transgender Female/Male-to-Female (MTF)", "Gender non-conforming (neither exclusively male nor female)", "Additional gender category / other, please specify", "Choose not to disclose"}
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="sex_at_birth",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *         enum={"Male", "Female", "Choose not to disclose", "unknown"}
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="pronoun",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"he/him", "she/her", "they/them"}
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="sexual_orientation",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Lesbian, gay or homosexual", "Straight or heterosexual", "Bisexual", "Something else, please describe", "Do not know", "Choose not to disclose"}
     *      )
     *     ),
     *      @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\MediaType(
     *       mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPatientSocialHistory(PatientSocialHistoryRequest $request)
    {
        return $this->patientHistoryRepository->setPatientSocialHistory($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/patient-social-history/{patient_id}",
     *      operationId="listPatientSocialHistory",
     *      tags={"Patient"},
     *      summary="List patient social history",
     *      description="List patient social history ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getPatientSocialHistory($patient_id)
    {
        return $this->patientHistoryRepository->getPatientSocialHistory($patient_id);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/delete-patient-social-history/{social_history_id}",
     *      operationId="deletePatientSocialHistory",
     *      tags={"Patient"},
     *      summary="Delete patient social history",
     *      description="Delete patient social history",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="social_history_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function deletePatientSocialHistory($social_history_id)
    {
        return $this->patientHistoryRepository->deletePatientSocialHistory($social_history_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/set-patient-allergy",
     *      operationId="addPatientAllergy",
     *      tags={"Patient"},
     *      summary="Add patient allergy ",
     *      description="Add and edit patient allergy ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_allergy_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="allergy_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="criticality",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Low", "High", "Unable to assess"}
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="reaction_severity",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Mild", "Moderate", "Severe"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="onset_date",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="note",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\MediaType(
     *       mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPatientAllergy(PatientAllergyRequest $request)
    {
        return $this->patientHistoryRepository->setPatientAllergy($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/patient-allergy/{patient_id}",
     *      operationId="listPatientAllergy",
     *      tags={"Patient"},
     *      summary="List patient social history",
     *      description="List patient social history",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getPatientAllergy($patient_id)
    {
        return $this->patientHistoryRepository->getPatientAllergy($patient_id);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/delete-patient-allergy/{patient_allergy_id}",
     *      operationId="deletePatientAllergy",
     *      tags={"Patient"},
     *      summary="Delete patient allergy",
     *      description="Delete patient allergy",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_allergy_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function deletePatientAllergy($patient_allergy_id)
    {
        return $this->patientHistoryRepository->deletePatientAllergy($patient_allergy_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/set-patient-privacy",
     *      operationId="addPatientPrivacy",
     *      tags={"Patient"},
     *      summary="Add Patient Privacy ",
     *      description=" Add and edit patient Privacy ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_privacy_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="notice",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="consent_call",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="patient_notes",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\MediaType(
     *       mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPatientPrivacy(PatientPrivacyRequest $request)
    {
        return $this->patientHistoryRepository->setPatientPrivacy($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/patient-privacy/{patient_id}",
     *      operationId="listPatientPrivacy",
     *      tags={"Patient"},
     *      summary="List patient privacy",
     *      description="List patient Privacy",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getPatientPrivacy($patient_id)
    {
        return $this->patientHistoryRepository->getPatientPrivacy($patient_id);
    }

    /**
     *   @OA\Get(
     *      path="/backend/api/patient/delete-patient-privacy/{social_privacy_id}",
     *      operationId="deletePatientPrivacy",
     *      tags={"Patient"},
     *      summary="Delete patient privacy",
     *      description="Delete patient privacy",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="social_privacy_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function deletePatientPrivacy($patient_privacy_id)
    {
        return $this->patientHistoryRepository->deletePatientPrivacy($patient_privacy_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/set-patient-contact",
     *      operationId="addPatientContact",
     *      tags={"Patient"},
     *      summary="Add patient contact ",
     *      description="Add and edit patient contact ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_contact_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="address_line_1",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="address_line_2",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="zip_code",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="country_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="city_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="state_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="home_country_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="home_phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="work_country_code",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="work_phone_number",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="consent_to_text",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Yes", "No"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="contact_preference",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *        enum={"Male", "Home phone", "Work phone",  "Mail", "Portal"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="patient_email",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\MediaType(
     *       mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPatientContact(PatientContactRequest $request)
    {
        return $this->patientHistoryRepository->setPatientContact($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/patient-contact/{patient_id}",
     *      operationId="listPatientContact",
     *      tags={"Patient"},
     *      summary="List patient contact",
     *      description="List patient contact",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function getPatientContact($patient_id)
    {
        return $this->patientHistoryRepository->getPatientContact($patient_id);
    }
    /**
     *   @OA\Get(
     *      path="/backend/api/patient/delete-patient-contact/{patient_contact_id}",
     *      operationId="deletePatientContact",
     *      tags={"Patient"},
     *      summary="Delete patient contact",
     *      description="Delete patient contact",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_contact_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function deletePatientContact($patient_contact_id)
    {
        return $this->patientHistoryRepository->deletePatientContact($patient_contact_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/set-patient-demography",
     *      operationId="addPatientDemography",
     *      tags={"Patient"},
     *      summary="Add and edit Patient Demography ",
     *      description="first time you create you pass patient_demography_id  null value and after  that you pass those create id for editing  ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_demography_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="language_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="race_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="ethnicity_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="marital_status",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *        enum={"Unknown", "Married", "Single", "Divorced", "Separated", "Widowed", "Partner"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="sexual_orientation",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *         enum={"Lesbian, gay or homosexual", "Straight or heterosexual", "Bisexual", "Something else, please describe", "Do not know", "Choose not to disclose"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="gender_identity",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *      enum={"Identifies as Male", "Identifies as Female", "Transgender Male/Female-to-Male (FTM)", "Transgender Female/Male-to-Female (MTF)", "Gender non-conforming (neither exclusively male nor female)", "Additional gender category / other, please specify", "Choose not to disclose"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="assigned_sex_at_birth",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *          enum={"Male", "Female", "Choose not to disclose", "unknown"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="pronoun",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"he/him", "she/her", "they/them"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="home_bound",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Yes", "No"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="family_size",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="income",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="income_define_per",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Year", "Month", "2 Weeks", "Week" , "Hourly", "Choose not to disclose"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="agricultural_worker",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="homeless_status",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="school_based_health_center_patient",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="veteran_status",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="public_housing_patient",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\MediaType(
     *       mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPatientDemography(PatientDemographyRequest $request)
    {
        return $this->patientHistoryRepository->setPatientDemography($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/patient-information/{patient_id}",
     *      operationId="listPatientInformation",
     *      tags={"Patient"},
     *      summary="List patient information",
     *      description="List patient information ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getPatientInformation($patient_id)
    {
        return $this->patientHistoryRepository->getPatientInformation($patient_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/allergy",
     *      operationId="listAllAllergy",
     *      tags={"Patient"},
     *      summary="List all allergy",
     *      description="List all allergy",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="search",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getAllAllergy(Request $request)
    {
        return $this->patientHistoryRepository->getAllAllergy($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/ethnicity",
     *      operationId="listAllEthnicity",
     *      tags={"Patient"},
     *      summary="List all ethnicity",
     *      description="List all ethnicity",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getAllEthnicity()
    {
        return $this->patientHistoryRepository->getAllEthnicity();
    }

    /**
     * @OA\Get(
     *      path="/backend/api/race",
     *      operationId="listAllRace",
     *      tags={"Patient"},
     *      summary="List all race",
     *      description="List all race",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getAllRaces()
    {
        return $this->patientHistoryRepository->getAllRaces();
    }

    /**
     * @OA\Get(
     *      path="/backend/api/language",
     *      operationId="listAllLanguage",
     *      tags={"Patient"},
     *      summary="List all language",
     *      description="List all language",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getAllLanguages()
    {
        return $this->patientHistoryRepository->getAllLanguages();
    }

    /**
     * @OA\Get(
     *      path="/backend/api/route",
     *      operationId="listAllRoute",
     *      tags={"Patient"},
     *      summary="List all route",
     *      description="List all route",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getAllRoutes()
    {
        return $this->patientHistoryRepository->getAllRoutes();
    }

    /**
     * @OA\Get(
     *      path="/backend/api/site",
     *      operationId="listAllSite",
     *      tags={"Patient"},
     *      summary="List all site",
     *      description="List all site",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getAllSite()
    {
        return $this->patientHistoryRepository->getAllSite();
    }

    /**
     * @OA\Post(
     *      path="/backend/api/vaccine-manufacture",
     *      operationId="listVaccineManufacture",
     *      tags={"Patient"},
     *      summary="List vaccine Manufacture",
     *      description=" List vaccine Manufacture",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="search",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getVaccineManufacture(Request $request)
    {
        return $this->patientHistoryRepository->getVaccineManufacture($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/vaccine-ndc",
     *      operationId="listVaccineNdc",
     *      tags={"Patient"},
     *      summary="List vaccine national drug code",
     *      description="List vaccine national drug code",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="search",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getVaccineNationalDrugCode(Request $request)
    {
        return $this->patientHistoryRepository->getVaccineNationalDrugCode($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/country",
     *      operationId="listAllCountry",
     *      tags={"Patient"},
     *      summary="List all country",
     *      description="List all country",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getAllCountry()
    {
        return $this->patientHistoryRepository->getAllCountry();
    }

    /**
     * @OA\Get(
     *      path="/backend/api/country-state/{country_id}",
     *      operationId="listAllCountryState",
     *      tags={"Patient"},
     *      summary="List all country state",
     *      description="List all country state",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="country_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getCountryState($country_id)
    {
        return $this->patientHistoryRepository->getCountryState($country_id);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/state-city/{state_id}",
     *      operationId="listAllStateCity",
     *      tags={"Patient"},
     *      summary="List all state city",
     *      description="List all state city",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="state_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getStateCity($state_id)
    {
        return $this->patientHistoryRepository->getStateCity($state_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/set-patient-employment",
     *      operationId="addPatientEmployment",
     *      tags={"Patient"},
     *      summary="Add and edit patient employment ",
     *      description="first time you create you pass employment_id  null in swagger you pass 0 value and after  that you pass those create id for editing  ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="employment_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="occupation",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="employer_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="employer_address",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="industry",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="zip_code",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="country_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="city_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="state_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="country_code",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="phone",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\MediaType(
     *       mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPatientEmployment(PatientEmploymentRequest $request)
    {
        return $this->patientHistoryRepository->setPatientEmployment($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/patient/patient-employment/{patient_id}",
     *      operationId="getPatientEmployment",
     *      tags={"Patient"},
     *      summary="get Patient Employment ",
     *      description="by passing patient_id ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getPatientEmployment($patient_id)
    {
        return $this->patientHistoryRepository->getPatientEmployment($patient_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/patient-reference-contact",
     *      operationId="listAllPatientReferenceContact",
     *      tags={"Patient"},
     *      summary="List all patient reference contact",
     *      description="List all patient reference contact like emergency contact, next to kin etc etc ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="contact_reference",
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
    public function getPatientReferenceContact(PatientReferenceGetRequest $request)
    {
        return $this->patientHistoryRepository->getPatientReferenceContact($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/practice-patient/{practice_id}",
     *      operationId="listAllPracticePatient",
     *      tags={"Patient"},
     *      summary=" All practice patient ",
     *      description="All patient list relate to practice ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="practice_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *       @OA\Parameter(
     *          name="pagination",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="first_name",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="middle_name",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="last_name",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="patient_key",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="phone_number",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="status",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="search",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
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
    public function getPracticePatient(Request $request, $practice_id)
    {
        return $this->patientHistoryRepository->getPracticePatient($request, $practice_id);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/practice/patient/{patient_id}",
     *      operationId="listPatient",
     *      tags={"Patient"},
     *      summary="List patient",
     *      description="List patient ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getPatient($patient_id)
    {
        return $this->patientHistoryRepository->getPatient($patient_id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/patient-identification",
     *      operationId="addPatientIdentification",
     *      tags={"Patient"},
     *      summary="Add and edit patient identification",
     *      description="first time you create you pass patient_identification_id  null value and after  that you pass those create id for editing  ",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="patient_identification_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="patient_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="legal_first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="legal_last_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="legal_middle_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="suffix",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="legal_sex",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           enum={"Male", "Female", "Other"}
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="previous_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="dob",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="ssn",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="mother_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\MediaType(
     *       mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function setPatientIdentification(PatientIdentificationRequest $request)
    {
        return $this->patientHistoryRepository->setPatientIdentification($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/reaction",
     *      operationId="listAllReaction",
     *      tags={"Patient"},
     *      summary="List all reaction",
     *      description="List all reaction",
     *      security={{"passport":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function allReaction()
    {
        return $this->patientHistoryRepository->allReaction();
    }
}
