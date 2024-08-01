<?php

namespace App\Repositories\Doctor\Interfaces\Appointment;

use App\Http\Requests\Doctor\StoreDoctorRequest;

interface AppointmentRepositoryInterface
{
    public function appointmentList($request);
    public function doctorSlot();
    public function createAppointment($request);
    public function reSchedule($request);
    public function patientList($request);
    public function medicalProblemList();
    public function getAppointmentByIdAndDate($request);
    public function appointmentListMonthlyCount($request);
    public function appointmentListByMonth();
    public function appointmentListByMonthToPreviousDate();
    public function createZoomUser($request);
    public function patientAppointmentListForDoctor($request,$appointmentModel);
}
