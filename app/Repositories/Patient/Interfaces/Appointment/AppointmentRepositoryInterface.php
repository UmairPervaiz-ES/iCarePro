<?php

namespace App\Repositories\Patient\Interfaces\Appointment;

interface AppointmentRepositoryInterface
{
public function appointmentList($request);
public function reSchedule($request);
public function practiceList();
public function doctorList($request);
public function appointmentCreate($request);
public function doctorSpecializationsList($request,$doctorModel, $specializationModel);
public function specializationList($request);
public function specializationsWithDoctor($request,$doctorPracticeModel, $specializationModel);
public function doctorSlot($doctor_id, $request);
public function medicalProblemList();
public function getAppointmentByIdAndDate($request,$appointmentModel);
public function getAppointmentList($request);
public function appointmentListByMonth();
public function appointmentListByMonthToPreviousDate();








}
