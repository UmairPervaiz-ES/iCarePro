<?php

namespace App\Repositories\Doctor\Interfaces;

interface DoctorRepositoryInterface
{

    public function getDetailsByID($id);

    public function listOfSlots($request, $id);

    public function requestOtpToUpdatePrimaryEmail($request);

    public function updatePrimaryEmail($request);

    public function doctorFee($request, $id);

    public function addDoctorFee($request);

    public function updateDoctorFeeStatus($request, $id);

    public function addSlot($request);

    public function publishSlot($request);

    public function deactivateSlot($request);

    public function listOfOffDates($doctor_id, $pagination);

    public function addOffDates($request);

    public function deleteOffDates($request);

    public function deleteDocument($request);

    public function uploadDocument($request);

    public function updateSpecialization($request);

    public function updatePersonalInformation($request);

    public function updateAboutMe($request);

    public function updateContactInformation($request);

    public function updateCurrentAddress($request);

    public function doctorPatientList($request);

    public function doctorDashboardStats();

    public function doctorAppointmentPiChart();

    public function listOfDoctorRegistrationRequests($pagination);

    public function updateDoctorRegistrationRequestStatus($request);

    public function allNotifications($request);

    public function markNotificationAsRead($request);

    public function markAllNotificationsAsRead($request);

    public function calendarAppointmentsViewDates($request);
}
