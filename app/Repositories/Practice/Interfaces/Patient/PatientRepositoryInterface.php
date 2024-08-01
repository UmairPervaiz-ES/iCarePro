<?php

namespace App\Repositories\Practice\Interfaces\Patient;

interface PatientRepositoryInterface
{
    public function patientAppointmentList($request,$appointmentModel);
    public function patientAppointmentDetails($request);
    public function allNotifications($request);
    public function markNotificationAsRead($request);
    public function markAllNotificationsAsRead($request);
}
