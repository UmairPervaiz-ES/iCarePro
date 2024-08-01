<?php

namespace App\Repositories\EPrescription\Interfaces\EPrescription;

interface EPrescriptionRepositoryInterface
{
    public function setDrugToPrescription($request);

    public function removeDrugFromPrescription($request);

    public function setLabTestToPrescription($request);

    public function removeLabTestFromPrescription($request);

    public function setProcedureToPrescription($request);

    public function removeProcedureFromPrescription($request);

    public function setNotesPrescription($request);

    public function setPracticeProcedures($request);

    public function removePracticeProcedures($request);

    public function setPracticeLabTests($request);

    public function removePracticeLabTests($request);

    public function viewEPrescriptionByPatientId($request);

    public function viewPrescriptionByEPrescriptionId($id);

    public function getProcedureForDropdown($request);

    public function getDrugsForDropdown($request);

    public function getLabTestsForDropdown($request);

    public function getDrugByDrugId($request);

    public function generateEPrescription($request);

    public function changeAppointmentStatus($request);

    public function ePrescriptionTemplateData();

    public function setEPrescriptionTemplateData($request);

    public function addDrug($request);

    public function generalMedicalProblems();
}
