<?php

namespace App\Repositories\Patient\Interfaces\Register;

interface RegisterRepositoryInterface
{
    public function registerPatient($request);


    public function checkPatientExist($request);
    public function checkPatientLogin($request);
    public function sendMobileVerificationCode($request);
    public function verifyMobileVerificationCode($request);
    public function setPassword($request);
    public function updatePassword($request);
    public function editPatientBasicInformation($request);
    public function changePhoneNumber($request);
    public function verifyOtp($request);
    public function sendOTP();
}
