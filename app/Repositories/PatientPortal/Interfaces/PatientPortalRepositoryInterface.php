<?php

namespace App\Repositories\PatientPortal\Interfaces;

interface PatientPortalRepositoryInterface 
{
    public function viewEPrescriptionByPatientId();

    public function viewPrescriptionByEPrescriptionId($request);

    public function getPatientVitals($request);

    public function generateEPrescription($request , $EPrescription);

    public function changeAppointmentStatus($request);
    
}