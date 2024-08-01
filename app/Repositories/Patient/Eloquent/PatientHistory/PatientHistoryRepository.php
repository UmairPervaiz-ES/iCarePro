<?php

namespace App\Repositories\Patient\Eloquent\PatientHistory;

use App\Filters\Patient\FirstName;
use App\Filters\Patient\LastName;
use App\Filters\Patient\MiddleName;
use App\Filters\Patient\Patientkey;
use App\Filters\Patient\PhoneNumber;
use App\Filters\Patient\Search;
use App\Filters\Patient\Status;
use App\libs\Messages\PatientGlobalMessageBook as PGMBook;
use App\Models\{Patient\Allergy, City\City, Patient\CommonPatientContact, Country\Country, Patient\Ethnicity, Language\Language, EPrescription\Manufacture, Patient\MedicalProblem, Patient\NationalDrugCode, Patient\Patient, Patient\PatientAllergy, Patient\PatientAllergyReaction, Patient\PatientContact, Patient\PatientFamilyHistory, Patient\PatientFamilyMedicalHistory, Patient\PatientMedicalProblem,  Patient\PatientPrivacy, Patient\PatientRelationship, Patient\PatientSocialHistory, Patient\PatientSurgicalHistory, Patient\PatientVaccine, Practice\Practice, Patient\Race, Patient\Reaction, Patient\Route, Patient\Site, State\State, Patient\SurgeryProcedure, Patient\Vaccine};
use App\Repositories\Patient\Interfaces\PatientHistory\PatientHistoryRepositoryInterface;
use App\Traits\CreateOrUpdate;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;

class PatientHistoryRepository implements PatientHistoryRepositoryInterface
{
    use RespondsWithHttpStatus;
    use CreateOrUpdate;

    /**
     *  Description: Validate user credentials and returns user data
     * 1) This method is used to create and update the patient basic reference contact
     * 2) send request field and also validate this request for required field
     * 3) If credentials are wrong invalid credentials message will return error message
     * 4) In case of valid credentials, check the contact_id
     * 5) If contact_id is null so create new record
     * 6) If contact_id is pass to existing id so that update those id record
     * 7) In case of any exception error message in response is return
     * @param  mixed $request
     * @return Response
     */
    public function setPatientReferenceContact($request): Response
    {
        $key = ['id' => $request['contact_id']];

        $patient_contacts = $this->createOrUpdate('Patient\CommonPatientContact', $request->validated(), $key);

        if ($patient_contacts->wasRecentlyCreated) {
            $response_message = PGMBook::SUCCESS['PATIENT_INFORMATION_CREATE'];
            $status = 201;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_INFORMATION_UPDATE'];
            $status = 200;
        }
        return $this->response($request, $patient_contacts, $response_message, $status);
    }

    /**
     * Description:  get all patient reference contact
     * 1) by passing patient_id
     * 2) if exist so return other wise return not found
     * @param  mixed $patient_id
     * @return Response
     */
    public function allReferenceContact($patient_id)
    {
        $contacts = Patient::where('id', $patient_id)->with('commonContact.country', 'commonContact.state', 'commonContact.city')->get();

        return $this->response($patient_id, $contacts, PGMBook::SUCCESS['PATIENT_REFERENCE_CONTACT'], 200);
    }

    /**
     * Description: Delete Reference contact by passing reference contact id
     * 1) pass contact_id if exist so delete
     * 2) if not exist return not found
     * @param  mixed $contact_id
     * @return Response
     */
    public function deletePatientReferenceContact($contact_id)
    {
        $contact = CommonPatientContact::find($contact_id);

        if (empty($contact)) {
            $response_message = PGMBook::FAILED['INFORMATION_FOUND'];
            $status = 400;
            $data = false;
            $success = false;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_INFORMATION_DELETE'];
            $status = 200;
            $data = null;
            $success = true;
            //delete contact
            $contact->delete();
        }
        return $this->response($contact_id, $data, $response_message, $status, $success);
    }

    /**
     *  Description: Validate user credentials and returns user data
     * 1) This method is used to create and update the patient family history
     * 2) send request field and also validate this request for required field
     * 3) If credentials are wrong invalid credentials message will return message
     * 4) Two type data pass first object and 2nd array so we create multiple array data using for loop
     * 5) In case of valid credentials, check the patient_medical_history_id
     * 6) If patient_medical_history_id is null so create new record
     * 7) If patient_medical_history_id is pass to existing id so that update those id record
     * 8) If you pass for array also if pass id is null so create if pass existing id so update
     * 9)If not pass existing id so delete those record
     * 10) In case of any exception error message in  response is return
     * @param  mixed $request
     * @return Response
     */
    public function setPatientFamilyHistory($request): Response
    {
        $key = ['id' => $request['patient_medical_history_id']];
        $data = [
            'patient_id' => $request['patient_id'],
            'medical_problem_id' => $request['medical_problem_id'],
        ];
        $patient_medical = $this->createOrUpdate('Patient\PatientFamilyMedicalHistory', $data, $key);
        $id = $patient_medical->id;

        foreach ($request['patient_family_history'] as $family) {
            $key = ['patient_family_medical_history_id' => $id, 'id' => $family['id']];
            $data = [
                'patient_family_medical_history_id' => $id,
                'patient_relationship_id' => $family['patient_relationship_id'],
                'onset_age' => $family['onset_age'],
                'died' => $family['died'],
                'note' => $family['note'],
            ];
            $patient_family_history = $this->createOrUpdate('Patient\PatientFamilyHistory', $data, $key);
            $ids[] = $patient_family_history->id;
        }
        if ($patient_family_history->wasRecentlyCreated) {
            $response_message = PGMBook::SUCCESS['PATIENT_FAMILY_HISTORY_CREATE'];
            $status = 201;
        } else {
            PatientFamilyHistory::where('patient_family_medical_history_id', $id)->whereNotIn('id', $ids)->delete();
            $response_message = PGMBook::SUCCESS['PATIENT_FAMILY_HISTORY_UPDATE'];
            $status = 200;
        }
        $family_history = PatientFamilyMedicalHistory::where('id', $id)->with('medicalProblem', 'PatientFamilyHistory.patientRelationship')->first();

        return $this->response($request, $family_history, $response_message, $status);
    }

    /**
     * Description:  get all patient Family History Detail with reference to patient
     * 1) by passing patient_id
     * @param  mixed $patient_id
     * @return Response
     */
    public function allFamilyHistory($patient_id)
    {
        $family_history = PatientFamilyMedicalHistory::where('patient_id', $patient_id)->with('medicalProblem', 'PatientFamilyHistory.patientRelationship')->latest('id')->paginate(20);

        return $this->response($patient_id, $family_history, PGMBook::SUCCESS['PATIENT_FAMILY_HISTORY'], 200);
    }

    /**
     * Description: Delete Patient Family History  by passing reference patient_history table id
     * 1) If history_id exist so delete
     * 2) If not exist return not found
     * @param  mixed $history_id
     * @return Response
     */
    public function deleteFamilyHistory($history_id): Response
    {
        $history = PatientFamilyMedicalHistory::find($history_id);

        if (empty($history)) {
            $response_message = PGMBook::FAILED['FAMILY_HISTORY_NOT_FOUND'];
            $status = 400;
            $data = false;
            $success = false;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_FAMILY_HISTORY_DELETE'];
            $status = 200;
            $data = null;
            $success = true;

            $history->delete();
        }
        return $this->response($history_id, $data, $response_message, $status, $success);
    }

    /**
     *  Description: Validate user credentials and returns user data
     * 1) This method is used to create and update the patient medical problem
     * 2) send request field and also validate this request for required field
     * 3) If credentials are wrong invalid credentials message will return message
     * 4) In case of valid credentials, check the patient_medical_problem_id
     * 5) If patient_medical_problem_id is null so create new record
     * 6) If patient_medical_problem_id is pass to existing id so that update those id record
     * 7) In case of any exception error in response is return
     * @param  mixed $request
     * @return Response
     */
    public function setPatientMedicalProblem($request): Response
    {
        $key = ['id' => $request['patient_medical_problem_id']];

        $medical_problem = $this->createOrUpdate('Patient\PatientMedicalProblem', $request->validated(), $key);

        if ($medical_problem->wasRecentlyCreated) {
            $response_message = PGMBook::SUCCESS['MEDICAL_PROBLEM_CREATE'];
            $status = 201;
        } else {
            $response_message = PGMBook::SUCCESS['MEDICAL_PROBLEM_UPDATE'];
            $status = 200;
        }
        $patient_medical_problem = PatientMedicalProblem::where('patient_id', $request['patient_id'])->with('medicalProblem')->find($medical_problem->id);

        return $this->response($request, $patient_medical_problem, $response_message, $status);
    }

    /**
     * Description:  get all patient Medical Problems Detail
     * 1) by passing patient_id
     * 2) check this patient_id in patient_medical_problems table
     * 3) and show data against this patient_id
     * @param  mixed $patient_id
     * @return Response
     */
    public function allPatientMedicalProblem($patient_id): Response
    {
        $patient_medical_problem = PatientMedicalProblem::where('patient_id', $patient_id)->with('medicalProblem')->latest('id')->paginate(20);

        return $this->response($patient_id, $patient_medical_problem, PGMBook::SUCCESS['MEDICAL_PROBLEM_DATA'], 200);
    }

    /**
     * Description:  Delete Patient Medical Problem
     * 1) by passing reference patient_medical_problem id in url
     * @param  mixed $problem_id
     * @return Response
     */
    public function deletePatientMedicalProblem($problem_id): Response
    {
        $patient_problem = PatientMedicalProblem::find($problem_id);

        if (empty($patient_problem)) {
            $response_message = PGMBook::FAILED['FAMILY_HISTORY_NOT_FOUND'];
            $status = 400;
            $data = false;
            $success = false;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_FAMILY_HISTORY_DELETE'];
            $status = 200;
            $data = null;
            $success = true;

            $patient_problem->delete();
        }
        return $this->response($problem_id, $data, $response_message, $status, $success);
    }

    /**
     *  Description: Validate user credentials and returns user data
     * 1) This method is used to create and update the patient surgical history
     * 2) send request field and also validate this request for required field
     * 3) If credentials are wrong invalid credentials message will return message
     * 4) In case of valid credentials, check the patient_surgery_id
     * 5) If patient_surgery_id is null so create new record
     * 6) If patient_surgery_id is pass to existing id so that update those id record
     * 7) In case of any exception error in response is return
     * @param  mixed $request
     * @return Response
     */
    public function setPatientSurgicalHistory($request): Response
    {
        $key = ['id' => $request['patient_surgery_id']];

        $patient_surgery = $this->createOrUpdate('Patient\PatientSurgicalHistory', $request->validated(), $key);

        if ($patient_surgery->wasRecentlyCreated) {
            $response_message = PGMBook::SUCCESS['PATIENT_SURGERY_CREATE'];
            $status = 201;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_SURGERY_UPDATE'];
            $status = 200;
        }
        $patient_surgery_history = PatientSurgicalHistory::where('id', $patient_surgery->id)->with('surgeryProcedure')->first();

        return $this->response($request, $patient_surgery_history, $response_message, $status);
    }

    /**
     * Description: get all patient surgeries
     * 1) by passing patient_id
     * 2) check this patient_id in patient_surgery_histories table
     * 3) and show data against this patient_id
     * @param  mixed $patient_id
     * @return Response
     */
    public function allPatientSurgicalHistory($patient_id): Response
    {
        $patient_surgery_history = PatientSurgicalHistory::where('patient_id', $patient_id)->with('surgeryProcedure')->latest('id')->paginate(20);

        return $this->response($patient_id, $patient_surgery_history, PGMBook::SUCCESS['PATIENT_SURGERY_DATA'], 200);
    }

    /**
     * Description: Delete Patient Medical Problem by passing reference patient_medical_problem id  in url
     * 1)If id is exist so delete
     * 2)If id is not exist return not found
     * @param  mixed $surgery_id
     * @return Response
     */
    public function deletePatientSurgicalHistory($surgery_id): Response
    {
        $patient_surgery_history = PatientSurgicalHistory::find($surgery_id);

        if (empty($patient_surgery_history)) {
            $response_message = PGMBook::FAILED['PATIENT_SURGERY_NOT_FOUND'];
            $status = 400;
            $data = false;
            $success = false;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_SURGERY_DELETE'];
            $status = 200;
            $data = null;
            $success = true;

            $patient_surgery_history->delete();
        }
        return $this->response($surgery_id, $data, $response_message, $status, $success);
    }

    /**
     * Description: Get all Vaccine name pass in request search parameter some keywords
     * 1) On the base of that keyword show data with 20 per page pagination
     *
     * @param  mixed $request
     * @return Response
     */
    public function allVaccine($request): Response
    {
        $vaccine = Vaccine::where('name', 'iLIKE', '%' . $request['search'] . '%')->latest('id')->paginate(50);

        return $this->response($request, $vaccine, PGMBook::SUCCESS['VACCINE'], 200);
    }

    /**
     * Description: Get all surgery name pass in request search parameter some keywords
     * 1) On the base of that pass keywords show data with 20 per page pagination
     *
     * @return Response
     */
    public function allSurgery($request): Response
    {
        $surgery_procedure = SurgeryProcedure::where('surgery_name', 'iLIKE', '%' . $request['search'] . '%')->latest('id')->paginate(50);

        return $this->response($request, $surgery_procedure, PGMBook::SUCCESS['SURGERY_PROCEDURE'], 200);
    }

    /**
     * Description: Validate user credentials and returns user data
     * 1) This method is used to create and update the patient vaccine
     * 2) send request field and also validate this request for required field
     * 3) If credentials are wrong invalid credentials message will return message
     * 4) In case of valid credentials, check the patient_vaccine_id
     * 5) If patient_vaccine_id is null so create new record
     * 6) If patient_vaccine_id is pass to existing id so that update those id record
     * 7) In case of any exception error in response is return
     * @param  mixed $request
     * @return Response
     */
    public function setPatientVaccine($request): Response
    {
        $key = ['id' => $request['patient_vaccine_id']];

        $patient_vaccine = $this->createOrUpdate('Patient\PatientVaccine', $request->validated(), $key);

        if ($patient_vaccine->wasRecentlyCreated) {
            $response_message = PGMBook::SUCCESS['PATIENT_VACCINE_CREATED'];
            $status = 201;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_VACCINE_UPDATE'];
            $status = 200;
        }

        $patient_vaccine_ = PatientVaccine::where('id', $patient_vaccine->id)->with('vaccine', 'route', 'site', 'manufacture', 'nationalDrugCode')->first();

        return $this->response($request, $patient_vaccine_, $response_message, $status);
    }

    /**
     *  Description:Get all Patient Vaccine by passing patient id
     *  1) also return patient vaccine with 20 per page pagination
     * @param  mixed $patient_id
     * @return Response
     */
    public function allPatientVaccine($patient_id): Response
    {
        $patient_vaccine = PatientVaccine::where('patient_id', $patient_id)->with('vaccine', 'route', 'site', 'manufacture', 'nationalDrugCode')->latest('id')->paginate(20);

        return $this->response($patient_id, $patient_vaccine, PGMBook::SUCCESS['PATIENT_VACCINE'], 200);
    }

    /**
     *  Description: Delete Patient Vaccine by passing reference patient_vaccine id
     * 1)If id is exist so delete
     * 2)If id is not exist return not found
     * @param  mixed $patient_vaccine_id
     * @return Response
     */
    public function deletePatientVaccine($patient_vaccine_id): Response
    {
        $patient_vaccine = PatientVaccine::find($patient_vaccine_id);

        if (empty($patient_vaccine)) {
            $response_message = PGMBook::FAILED['PATIENT_VACCINE_NOT_FOUND'];
            $status = 400;
            $data = false;
            $success = false;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_VACCINE_DELETE'];
            $status = 200;
            $data = null;
            $success = true;

            $patient_vaccine->delete();
        }
        return $this->response($patient_vaccine_id, $data, $response_message, $status, $success);
    }

    /**
     *  Description: Get All Medical Problems  pass in request search parameter some keywords
     * 1) On the base of that keyword show data with 20 per page pagination
     * @return Response
     */
    public function allMedicalProblem($request): Response
    {
        $medical_problems = MedicalProblem::where('name', 'iLIKE', '%' . $request['search'] . '%')->where('is_general' , null)->latest('id')->paginate(50);

        return $this->response($request, $medical_problems, PGMBook::SUCCESS['MEDICAL_PROBLEM'], 200);
    }

    /**
     * Description: Get All Relationships
     * @return Response
     */
    public function allRelationship(): Response
    {
        $relationships = PatientRelationship::get();

        return $this->response(true, $relationships, PGMBook::SUCCESS['RELATIONSHIPS'], 200);
    }

    /**
     *  Description: Validate user credentials and returns user data
     * 1) This method is used to create and update the patient Social History
     * 2) send request field and also validate this request for required field
     * 3) If credentials are wrong invalid credentials message will return message
     * 4) In case of valid credentials, check the patient_social_id
     * 5) If patient_social_id is null so create new record
     * 6) If patient_social_id is pass to existing id so that update those id record
     * 7) In case of any exception error in response is return
     * @param  mixed $request
     * @return Response
     */
    public function setPatientSocialHistory($request): Response
    {
        $key = ['id' => $request['patient_social_id']];

        $patient_social_history = $this->createOrUpdate('Patient\PatientSocialHistory',  $request->validated(), $key);

        if ($patient_social_history->wasRecentlyCreated) {
            $response_message = PGMBook::SUCCESS['PATIENT_SOCIAL_HISTORY_CREATED'];
            $status = 201;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_SOCIAL_HISTORY_UPDATE'];
            $status = 200;
        }

        return $this->response($request, $patient_social_history, $response_message, $status);
    }

    /**
     *  Description: Get the Social History of Patient
     * 1) By passing patient_id
     * @param  mixed $patient_id
     * @return Response
     */
    public function getPatientSocialHistory($patient_id): Response
    {
        $patient_social_history = PatientSocialHistory::where('patient_id', $patient_id)->first();

        return $this->response($patient_id, $patient_social_history, PGMBook::SUCCESS['PATIENT_SOCIAL_HISTORY'], 200);
    }

    /**
     *  Description: Delete Patient Vaccine by passing reference patient_vaccine id  in url
     * 1)If id is exist so delete
     * 2)If id is not exist return not found
     * @param  mixed $social_history_id
     * @return Response
     */
    public function deletePatientSocialHistory($social_history_id)
    {
        $patient_social_history = PatientSocialHistory::find($social_history_id);

        if (empty($patient_social_history)) {
            $response_message = PGMBook::FAILED['PATIENT_SOCIAL_HISTORY_NOT_FOUND'];
            $status = 400;
            $data = false;
            $success = false;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_SOCIAL_HISTORY_DELETE'];
            $status = 200;
            $data = null;
            $success = true;

            $patient_social_history->delete();
        }
        return $this->response($social_history_id, $data, $response_message, $status, $success);
    }

    /**
     * Description: Validate user credentials and returns user data
     * 1) This method is used to create and update the patient Allergy also create and update the allergy reactions
     * 2) send request field and also validate this request for required field
     * 3) If credentials are wrong invalid credentials message will return message
     * 4) Two type data pass first object and 2nd array so we create multiple array data using for loop
     * 5) In case of valid credentials, check the patient_medical_history_id
     * 6) If patient_allergy_id is null so create new record
     * 7) If patient_allergy_id is pass to existing id so that update those id record
     * 8) If you pass for array also if pass id is null so create if pass existing id so update
     * 9)If not pass existing id so delete those existing id record
     * 10) In case of any exception error in response is return
     * @param  mixed $request
     * @return Response
     */
    public function setPatientAllergy($request): Response
    {
        $key = ['id' => $request['patient_allergy_id']];
        $data = [
            'patient_id' => $request['patient_id'],
            'allergy_id' => $request['allergy_id'],
            'criticality' => $request['criticality'],
            'onset_date' => $request['onset_date'],
            'note' => $request['note'],
        ];
        $patient_allergy = $this->createOrUpdate('Patient\PatientAllergy', $data, $key);

        $id = $patient_allergy->id;

        foreach ($request['patient_allergy_reaction'] as $allergy) {
            $key = ['patient_allergy_id' => $id, 'id' => $allergy['id']];
            $data = [
                'patient_allergy_id' => $id,
                'reaction_id' => $allergy['reaction_id'],
                'reaction_severity' => $allergy['reaction_severity'],
            ];
            $patient_allergy_reaction =  $this->createOrUpdate('Patient\PatientAllergyReaction', $data, $key);
            $ids[] = $patient_allergy_reaction->id;
        }

        if ($patient_allergy->wasRecentlyCreated) {
            $response_message = PGMBook::SUCCESS['PATIENT_ALLERGY_CREATED'];
            $status = 201;
        } else {
            PatientAllergyReaction::where('patient_allergy_id', $request['patient_allergy_id'])->whereNotIn('id', $ids)->delete();

            $response_message = PGMBook::SUCCESS['PATIENT_ALLERGY_UPDATE'];
            $status = 200;
        }
        $patient_allergy1 = PatientAllergy::where('id', $id)->with('allergy', 'patientAllergyReaction.reaction')->first();

        return $this->response($request, $patient_allergy1, $response_message, $status);
    }

    /**
     * Description: Get the Patient Allergy by pass in request search parameter some keywords
     * 1) On the base of that keywords  show data with 20 per page pagination
     *
     * @param  mixed $patient_id
     * @return Response
     */
    public function getPatientAllergy($patient_id): Response
    {
        $patient_allergy = PatientAllergy::where('patient_id', $patient_id)->with('allergy', 'patientAllergyReaction.reaction')->latest('id')->paginate(20);
        return $this->response($patient_id, $patient_allergy, PGMBook::SUCCESS['PATIENT_ALLERGY'], 200);
    }

    /**
     * Description: Delete Patient Allergy by passing reference patient_allergy_id in url
     * 1)If id is exist so delete
     * 2)If id is not exist return not found
     * @param  mixed $patient_allergy_id
     * @return Response
     */
    public function deletePatientAllergy($patient_allergy_id)
    {
        $patient_allergy = PatientAllergy::find($patient_allergy_id);

        if (empty($patient_allergy)) {
            $response_message = PGMBook::FAILED['PATIENT_ALLERGY_NOT_FOUND'];
            $status = 400;
            $data = false;
            $success = false;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_ALLERGY_DELETE'];
            $status = 200;
            $data = null;
            $success = true;

            $patient_allergy->delete();
        }
        return $this->response($patient_allergy_id, $data, $response_message, $status, $success);
    }

    /**
     * Description: Validate user credentials and returns user data
     * 1) This method is used to create and update the patient Privacy
     * 2) send request field and also validate this request for required field
     * 3) If credentials are wrong invalid credentials message will return  message
     * 4) In case of valid credentials, check the patient_privacy_id
     * 5) If patient_privacy_id is null so create new record
     * 6) If patient_privacy_id is pass to existing id so that update those id record
     * 7) In case of any exception error is creation and update, and a response is return
     * @param  mixed $request
     * @return Response
     */
    public function setPatientPrivacy($request): Response
    {
        $key = ['id' => $request['patient_privacy_id']];

        $patient_privacy = $this->createOrUpdate('Patient\PatientPrivacy',  $request->validated(), $key);

        if ($patient_privacy->wasRecentlyCreated) {
            $response_message = PGMBook::SUCCESS['PATIENT_PRIVACY_CREATED'];
            $status = 201;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_PRIVACY_UPDATE'];
            $status = 200;
        }
        return $this->response($request, $patient_privacy, $response_message, $status);
    }

    /**
     * Description: Get patient privacy by passing patient id in url
     * 1) by passing patient_id
     * 2) check this patient_id in patient  table so
     * 3) and show data against this patient_id with patient privacy
     * @param  mixed $patient_id
     * @return Response
     */
    public function getPatientPrivacy($patient_id): Response
    {
        $patient_privacy = Patient::where('id', $patient_id)->with('patientPrivacy')->get();

        return $this->response($patient_id, $patient_privacy, PGMBook::SUCCESS['PATIENT_PRIVACY'], 200);
    }
    /**
     * Description: Delete Patient Vaccine by passing reference patient_vaccine id
     * 1) If patient_privacy_id exist in PatientPrivacy table so delete
     * 2) If not exist return no found
     * @param  mixed $patient_privacy_id
     * @return Response
     */
    public function deletePatientPrivacy($patient_privacy_id): Response
    {
        $patient_privacy = PatientPrivacy::find($patient_privacy_id);

        if (empty($patient_privacy)) {
            $response_message = PGMBook::FAILED['PATIENT_PRIVACY_NOT_FOUND'];
            $status = 400;
            $data = false;
            $success = false;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_PRIVACY_DELETE'];
            $status = 200;
            $data = null;
            $success = true;

            $patient_privacy->delete();
        }
        return $this->response($patient_privacy_id, $data, $response_message, $status, $success);
    }

    /**
     * Description: Validate user credentials and returns user data
     * 1) This method is used to create and update the patient Contact
     * 2) send request field and also validate this request for required field
     * 3) If credentials are wrong invalid credentials message will return  message
     * 4) In case of valid credentials, check the patient_contact_id
     * 5) If patient_contact_id is null so create new record
     * 6) If patient_contact_id is pass to existing id so that update those id record
     * 7) In case of any exception error is creation and update, and a response is return
     * @param  mixed $request
     * @return Response
     */
    public function setPatientContact($request): Response
    {
        $key = ['id' => $request['patient_contact_id']];

        $patient_contact = $this->createOrUpdate('Patient\PatientContact', $request->validated(), $key);

        if ($patient_contact->wasRecentlyCreated) {
            $response_message = PGMBook::SUCCESS['PATIENT_CONTACT_CREATED'];
            $status = 201;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_CONTACT_UPDATE'];
            $status = 200;
        }

        return $this->response($request, $patient_contact, $response_message, $status);
    }

    /**
     * Description: Description: Get patient contact by passing patient_id
     * 1) If patient_privacy_id exist in PatientContact table so delete
     * 2) If not exist return no found
     * @param  mixed $patient_id
     * @return Response
     */
    public function getPatientContact($patient_id): Response
    {
        $patient_contact = Patient::where('id', $patient_id)->with('patientContact.country', 'patientContact.state', 'patientContact.city')->first();

        return $this->response($patient_id, $patient_contact, PGMBook::SUCCESS['PATIENT_CONTACT'], 200);
    }

    /**
     * Description: Delete Patient Contact by passing  patient_contact_id in url
     * 1)If patient id exist so delete
     * 2)If not exist return not found
     * @param  mixed $patient_contact_id
     * @return Response
     */
    public function deletePatientContact($patient_contact_id): Response
    {
        $patient_contact = PatientContact::find($patient_contact_id);

        if (empty($patient_contact)) {
            $response_message = PGMBook::FAILED['PATIENT_CONTACT_NOT_FOUND'];
            $status = 400;
            $data = false;
            $success = false;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_CONTACT_DELETE'];
            $status = 200;
            $data = null;
            $success = true;

            $patient_contact->delete();
        }
        return $this->response($patient_contact_id, $data, $response_message, $status, $success);
    }

    /**
     * Description: Validate user credentials and returns user data
     * 1) This method is used to create and update the patient Demography
     * 2) send request field and also validate this request for required field
     * 3) If credentials are wrong invalid credentials message will return  message
     * 4) In case of valid credentials, check the patient_demography_id
     * 5) If patient_demography_id is null so create new record
     * 6) If patient_demography_id is pass to existing id so that update those id record
     * 7) In case of any exception error is creation and update, and a response is return
     * @param  mixed $request
     * @return Response
     */
    public function setPatientDemography($request): Response
    {
        $key = ['id' => $request['patient_demography_id']];

        $patient_demography = $this->createOrUpdate('Patient\PatientDemography', $request->validated(), $key);

        if ($patient_demography->wasRecentlyCreated) {
            $response_message = PGMBook::SUCCESS['PATIENT_DEMOGRAPHY_CREATED'];
            $status = 201;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_DEMOGRAPHY_UPDATE'];
            $status = 200;
        }
        return $this->response($request, $patient_demography, $response_message, $status);
    }

    /**
     * Description: Get Patient information by passing patient id in url
     * 1) by passing patient_id
     * 2) check this patient_id in patient  table so
     * 3) and show data against this patient_id with patient identification, patientContact and related of that relationship,
     * 4)  patientDemography and with related table relationship
     * 5) commonContact and with related table relationship
     * 6)  patientEmployment and with related table relationship
     * @param  mixed $patient_id
     * @return Response
     */
    public function getPatientInformation($patient_id): Response
    {
        $patient_demography = Patient::where('id', $patient_id)->with('patientIdentification', 'patientContact.country', 'patientContact.state', 'patientContact.city', 'patientDemography.language', 'patientDemography.race', 'patientDemography.ethnicity', 'commonContact.country', 'commonContact.state', 'commonContact.city', 'patientEmployment.country', 'patientEmployment.state', 'patientEmployment.city')->first();

        return $this->response($patient_id, $patient_demography, PGMBook::SUCCESS['PATIENT_INFORMATION'], 200);
    }

    /**
     * Description: Get All Allergy  pass in request search parameter some keywords
     *  1) On the base of that keywords show data with 50 per page pagination
     * @param  mixed $request
     * @return Response
     */
    public function getAllAllergy($request): Response
    {
        $allergies = Allergy::where('name', 'iLIKE', '%' . $request['search'] . '%')->latest('id')->paginate(50);

        return $this->response($request, $allergies, PGMBook::SUCCESS['ALLERGIES'], 200);
    }

    /**
     * Description:  Get All Ethnicity
     * @return Response
     */
    public function getAllEthnicity(): Response
    {
        $ethnicity = Ethnicity::get();

        return $this->response(true, $ethnicity, PGMBook::SUCCESS['ETHNICITY'], 200);
    }
    /**
     * Description: Get All Races
     * @return Response
     */
    public function getAllRaces(): Response
    {
        $races = Race::get();
        return $this->response(true, $races, PGMBook::SUCCESS['RACES'], 200);
    }

    /**
     *  Description: Get All Languages
     * @return Response
     */
    public function getAllLanguages(): Response
    {
        $languages = Language::get(['id', 'language']);

        return $this->response(true, $languages, PGMBook::SUCCESS['LANGUAGES'], 200);
    }

    /**
     *  Description: Get All Routes
     * @return Response
     */
    public function getAllRoutes(): Response
    {
        $routes = Route::get();

        return $this->response(true, $routes, PGMBook::SUCCESS['ROUTES'], 200);
    }

    /**
     * Description: Get All Sites
     * @return Response
     */
    public function getAllSite(): Response
    {
        $sites = Site::get();

        return $this->response(true, $sites, PGMBook::SUCCESS['SITES'], 200);
    }

    /**
     * Description: Get  Manufacture  pass in request search parameter some keywords
     * 1) on the base of that show data with 50 per page pagination
     * @param  mixed
     * @return Response
     */
    public function getVaccineManufacture($request): Response
    {
        $Vaccine_manufacture = Manufacture::where('name', 'iLIKE', '%' . $request['search'] . '%')->latest('id')->paginate(50);

        return $this->response($request, $Vaccine_manufacture, PGMBook::SUCCESS['VACCINE_MANUFACTURE'], 200);
    }

    /**
     * Description: Get  National Drug Code   pass in request search parameter some keywords
     * 1) on the base of that show data with 50 per page pagination
     * @param  mixed
     * @return Response
     */
    public function getVaccineNationalDrugCode($request): Response
    {
        $Vaccine_national_drug_code = NationalDrugCode::where('name', 'iLIKE', '%' . $request['search'] . '%')->latest('id')->paginate(50);

        return $this->response($request, $Vaccine_national_drug_code, PGMBook::SUCCESS['VACCINE_NDC'], 200);
    }

    /**
     * Description: get all countries
     * @return Response
     */
    public function getAllCountry(): Response
    {
        $countries = Country::where('is_active' , true)->get(['id', 'name']);

        return $this->response(true, $countries, PGMBook::SUCCESS['COUNTRY'], 200);
    }

    /**
     * Description: Get States against country by passing country_id
     * 1) Show those country against states
     * @param  mixed $country_id
     * @return Response
     */
    public function getCountryState($country_id): Response
    {
        $state = State::where('country_id', $country_id)->get();

        return $this->response($country_id, $state, PGMBook::SUCCESS['COUNTRY_STATE'], 200);
    }

    /**
     * Description: Set city against state by passing state_id
     * 1) Show those state against Cities
     * @param  mixed $state_id
     * @return Response
     */
    public function getStateCity($state_id): Response
    {
        $cities = City::where('state_id', $state_id)->get();

        return $this->response($state_id, $cities, PGMBook::SUCCESS['STATE_CITY'], 200);
    }

    /**
     * Description: Validate user credentials and returns user data
     * 1) This method is used to create and update the patient Employment
     * 2) send request field and also validate this request for required field
     * 3) If credentials are wrong invalid credentials message will return  message
     * 4) In case of valid credentials, check the employment_id
     * 5) If employment_id is null so create new record
     * 6) If employment_id is pass to existing id so that update those id record
     * 7) In case of any exception error is creation and update, and a response is return
     * @param  mixed $request
     * @return Response
     */
    public function setPatientEmployment($request): Response
    {
        $key = ['id' => $request['employment_id']];

        $employment = $this->createOrUpdate('Patient\PatientEmployment', $request->validated(), $key);

        if ($employment->wasRecentlyCreated) {
            $response_message = PGMBook::SUCCESS['PATIENT_EMPLOYMENT_CREATED'];
            $status = 201;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_EMPLOYMENT_UPDATE'];
            $status = 200;
        }

        return $this->response($request, $employment, $response_message, $status);
    }

    /**
     * Description: get patient employment by passing patient id
     * 1) show those patient employment record
     * @param  mixed $patient_id
     * @return Response
     */
    public function getPatientEmployment($patient_id)
    {
        $employment = Patient::where('id', $patient_id)->with('patientEmployment.country', 'patientEmployment.state', 'patientEmployment.city')->get();

        return $this->response($patient_id, $employment, PGMBook::SUCCESS['PATIENT_EMPLOYMENT'], 200);
    }

    /**
     * Description: get Patient Reference Contact by passing reference contact
     * 1) Like emergency contact next to kin guardian and patient id
     * 2) Check patient_id and against reference contact show data on the base of that
     * @param  mixed $request
     * @return Response
     */
    public function getPatientReferenceContact($request): Response
    {
        $reference = CommonPatientContact::where('patient_id', $request['patient_id'])->where('contact_reference', $request['contact_reference'])->with('country', 'state', 'city')->first();

        if (empty($reference)) {
            return $this->response(true, false, PGMBook::FAILED['INFORMATION_FOUND'], 200, false);
        }

        return $this->response($request, $reference, PGMBook::SUCCESS['PATIENT_INFORMATION_CONTACT'], 200);
    }

    /**
     * Description: Get Practice Patient by passing practice_id
     * 1) Show all those patient which against this practice_id
     * 2) This will return patient list against specific practice
     * @param  mixed $practice_id
     * @return Response
     */
    public function getPracticePatient($request, $practice_id)
    {
        $practiceId = $this->practice_id();

        $query = Patient::whereHas('practicePatient', function ($query) use ($practiceId) {
            $query->where('practice_id', $practiceId);
        })->latest('id');

        $patients = app(Pipeline::class)
            ->send($query)
            ->through([
                Search::class,
                FirstName::class,
                PhoneNumber::class,
                Patientkey::class,
                Status::class,
                MiddleName::class,
                LastName::class
            ])
            ->thenReturn()
            ->paginate($request->pagination);
        return $this->response($request, $patients, PGMBook::SUCCESS['PRACTICE_PATIENT'], 200);
    }

    /**
     * Description: get patient Details  by passing patient id
     * 1) When practice login and show our specific patient detail so
     * 2)  Practice pass those patient id that want to see
     * @param  mixed $patient_id
     * @return Response
     */
    public function getPatient($patient_id)
    {
        $practice = Practice::find($this->practice_id()); // login practice  data

        $patient = Patient::whereHas('practicePatient', function ($query) use ($patient_id, $practice) {
            $query->where('patient_id', $patient_id)->where('practice_id', $practice['id']);
        })->first();

        return $this->response($patient_id, $patient, PGMBook::SUCCESS['GET_PATIENT'], 200);
    }

    /**
     * Description: Validate user credentials and returns user data
     * 1) This method is used to create and update the patient Identification
     * 2) send request field and also validate this request for required field
     * 3) If credentials are wrong invalid credentials message will return  message
     * 4) In case of valid credentials, check the patient_identification_id
     * 5) If patient_identification_id is null so create new record
     * 6) If patient_identification_id is pass to existing id so that update those id record
     * 7) In case of any exception error is creation and update, and a response is return
     * @param  mixed $request
     * @return Response
     */
    public function setPatientIdentification($request): Response
    {
        $key = ['id' => $request['patient_identification_id']];

        $patient_identification = $this->createOrUpdate('Patient\PatientIdentification', $request->validated(), $key);

        if ($patient_identification->wasRecentlyCreated) {
            $response_message = PGMBook::SUCCESS['PATIENT_IDENTIFICATION_CREATE'];
            $status = 201;
        } else {
            $response_message = PGMBook::SUCCESS['PATIENT_IDENTIFICATION_UPDATE'];
            $status = 200;
        }

        return $this->response($request, $patient_identification, $response_message, $status);
    }

    /**
     * Description: Get all Reactions
     * @return Response
     */
    public function allReaction(): Response
    {
        $reaction = Reaction::get(['id', 'name']);

        return $this->response(true, $reaction, PGMBook::SUCCESS['PRACTICE_PATIENT'], 200);
    }
}
