<?php

namespace App\Repositories\Practice\Interfaces\Doctor;

interface DoctorRepositoryInterface
{
public function listOfDoctors($request);
public function doctorByID($id);
public function doctorPendingList();
public function doctorPendingListResponse($request,$id);
public function doctorSpecializations();
public function store($doctorRequest);
public function sendRegistrationLinkToDoctor($request);
public function listOfDoctorRequestsSent($pagination);
public function doctorAppointmentList($request);
public function calendarAppointmentsViewDates($request);
}
