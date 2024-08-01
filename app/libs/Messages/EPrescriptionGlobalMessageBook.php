<?php

namespace App\libs\Messages;

class EPrescriptionGlobalMessageBook
{
  const FAILED = [
    //  'TEST' => 'Sorry! Your account is not active, kindly verify your account and try to login again.',
    'PRESCRIBED_DRUG_NOT_FOUND' => 'No prescribed drug found.',
    'PRESCRIBED_LAB_TEST_NOT_FOUND' => 'No prescribed lab test found.',
    'PRESCRIBED_PROCEDURE_NOT_FOUND' => 'No prescribed procedure found.',
    'PROCEDURE_NOT_FOUND' => 'No procedure found.',
    'LAB_TEST_NOT_FOUND' => 'No lab test found.',
    'APPOINTMENT_NOT_FOUND' => 'No appointment found.',
    'TEMPLATE_DATA_NOT_FOUND' => 'Template data not exist.',
    'EPRESCRIPTION_NOT_FOUND' => 'No e-prescription found.',
    'DRUG_ADD' => 'No Drug added.',
  ];

  const SUCCESS = [
    'SET_DRUG_TO_PRESCRIPTION' => 'Drug saved in prescription.',
    'PRESCRIBED_DRUG_REMOVE' => 'Drug removed from prescription.',
    'PRESCRIBED_LAB_TEST_REMOVE' => 'Lab test removed from prescription.',
    'PRESCRIBED_PROCEDURE_REMOVE' => 'Procedure removed from prescription.',
    'PROCEDURE_REMOVE' => 'Procedure deleted.',
    'LAB_TEST_REMOVE' => 'Lab test deleted.',
    'SET_LAB_TEST_TO_PRESCRIPTION' => 'Lab test saved in prescription.',
    'SET_PROCEDURE_TO_PRESCRIPTION' => 'Procedure saved in prescription.',
    'SET_PROCEDURE' => 'Procedure saved.',
    'SET_LAB_TEST' => 'Lab test saved.',
    'SET_Notes_TO_PRESCRIPTION' => 'Notes saved in prescription.',
    'PDF_CREATED' => 'E-prescription created.',
    'APPOINTMENT_STATUS_CHANGE' => 'Appointment status changed.',
    'E_PRESCRIPTION_PID_FETCHED' => 'E-prescription list received.',
    'E_PRESCRIPTION_EID_FETCHED' => 'E-prescription detail received.',
    'DRUG_DID' => 'Drug detail received.',
    'GET_DRUG' => 'Drugs list received.',
    'GET_LAB_TEST' => 'Lab test list received.',
    'GET_PROCEDURE' => 'Procedure list received.',
    'TEMPLATE_DATA' => 'Template data received.',
    'TEMPLATE_DATA_CREATED' => 'Template data created.',
    'TEMPLATE_DATA_UPDATED' => 'Template data updated.',
    'DRUG_ADD' => 'Drug added successfully.',
    'GET_GENERAL_MEDICAL_PROBLEMS' => 'General medical problems received.',
  ];
}
