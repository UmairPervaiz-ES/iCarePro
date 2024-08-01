<?php

namespace App\Repositories\ConsentForm\Interfaces;

interface ConsentFormRepositoryInterface 
{

public function setConsentFormType($request);

public function setConsentForm($request);

public function consentForms();

public function publishedConsentForms();

public function addConsentLog($request);

public function consentLogResponse();

public function registerDoctorConsentForms();

public function registerDoctorPublishedConsentForms();

public function registerPatientConsentForms();

public function registerPatientPublishedConsentForms();

}