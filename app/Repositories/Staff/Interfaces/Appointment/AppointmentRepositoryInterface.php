<?php

namespace App\Repositories\Staff\Interfaces\Appointment;

use App\Helper\Doctor;

interface AppointmentRepositoryInterface 
{
   

public function createAppointment($request);
public function appointmentList($request);
public function reSchedule($request);
public function practiceDoctor($request);
public function doctorSlot($doctor_id);
public function specializationList($request);
public function specializationsWithDoctor($request ,$doctorModel,$specializationModel);
public function doctorSpecializationsList($request,$specializationModel);
public function medicalProblemList();
public function getAppointmentByIdAndDate($request);

}
