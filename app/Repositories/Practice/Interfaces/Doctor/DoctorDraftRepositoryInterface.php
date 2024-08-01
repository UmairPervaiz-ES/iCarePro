<?php

namespace App\Repositories\Practice\Interfaces\Doctor;

interface DoctorDraftRepositoryInterface
{
public function store($request);
public function createDoctorAsDraft($request);
public function updateDoctorDraft($request);

public function getDoctorDetails();
public function updateDoctorDraftByDoctor($request);
public function storeDoctorDraftByDoctor($request);
}
