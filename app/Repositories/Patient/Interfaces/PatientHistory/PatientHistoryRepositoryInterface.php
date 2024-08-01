<?php

namespace App\Repositories\Patient\Interfaces\PatientHistory;

interface PatientHistoryRepositoryInterface
{

    public function allReferenceContact($patient_id);
    public function setPatientReferenceContact($request);
    public function deletePatientReferenceContact($contact_id);

    public function setPatientFamilyHistory($request);
    public function allFamilyHistory($patient_id);
    public function deleteFamilyHistory($history_id);


    public function setPatientMedicalProblem($request);
    public function allPatientMedicalProblem($patient_id);
    public function deletePatientMedicalProblem($problem_id);

    public function setPatientSurgicalHistory($request);
    public function allPatientSurgicalHistory($patient_id);
    public function deletePatientSurgicalHistory($surgery_id);

    public function allVaccine($request);
    public function allSurgery($request);

    public function setPatientVaccine($request);
    public function allPatientVaccine($patient_id);
    public function deletePatientVaccine($patient_vaccine_id);

    public function allMedicalProblem($request);
    public function allRelationship();

    public function setPatientSocialHistory($request);
    public function getPatientSocialHistory($patient_id);
    public function deletePatientSocialHistory($social_history_id);

    public function setPatientAllergy($request);
    public function getPatientAllergy($patient_id);
    public function deletePatientAllergy($patient_allergy_id);

    public function setPatientPrivacy($request);
    public function getPatientPrivacy($patient_id);
    public function deletePatientPrivacy($patient_privacy_id);

    public function setPatientContact($request);
    public function getPatientContact($patient_id);
    public function deletePatientContact($patient_contact_id);

    public function setPatientDemography($request);
    public function getPatientInformation($patient_id);

    public function getAllAllergy($request);
    public function getAllEthnicity();
    public function getAllRaces();
    public function getAllLanguages();
    public function getAllRoutes();
    public function getAllSite();
    public function getVaccineManufacture($request);
    public function getVaccineNationalDrugCode($request);
    public function getAllCountry();
    public function getCountryState($country_id);
    public function getStateCity($state_id);

    public function setPatientEmployment($request);
    public function getPatientEmployment($patient_id);

    public function getPatientReferenceContact($request);
    public function getPracticePatient($request, $practice_id);
    public function getPatient($patient_id);

    public function setPatientIdentification($request);
    public function allReaction();
}
