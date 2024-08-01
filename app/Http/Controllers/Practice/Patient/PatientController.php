<?php

namespace App\Http\Controllers\Practice\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment\Appointment;
use App\Repositories\Practice\Interfaces\Patient\PatientRepositoryInterface;
use Illuminate\Http\Request;

class PatientController extends Controller
{

    private PatientRepositoryInterface $patientRepository;
    public function __construct(PatientRepositoryInterface $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    /**
     * @OA\Get(
     *      path="/backend/api/practice/patient-appointment-list",
     *      operationId="practicePatientAppointmentsList",
     *      tags={"Practice"},
     *
     *      summary="patient-appointment-list",
     *      description="patient-appointment-list",
     *     security={
     *         {"passport": {}},
     *   },
     *
     *     *  *   @OA\Parameter(
     *          name="appointment_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="date",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function patientAppointmentList(Request $request, Appointment $appointmentModel){

        return $this->patientRepository->patientAppointmentList($request,$appointmentModel);

    }


   /**
     * @OA\Get(
     *      path="/backend/api/practice/patient-appointment-details",
     *      operationId="patientAppointmentDetails",
     *      tags={"Practice"},
     *
     *      summary="patient appointment details",
     *      description="patient appointment details",
     *     security={
     *         {"passport": {}},
     *   },
     *
     *     *  *   @OA\Parameter(
     *          name="appointment_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
      *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function patientAppointmentDetails(Request $request){

        return $this->patientRepository->patientAppointmentDetails($request->all());

    }

    /**
     * @OA\Post (
     *      path="/backend/api/patient/notifications",
     *      operationId="patientNotifications",
     *      tags={"Patient"},
     *      summary="Retreving all patient notifications",
     *      description="Retreiving all notifications",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="pagination",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *     ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function allNotifications(Request $request)
    {
        return $this->patientRepository->allNotifications($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/markNotificationAsRead",
     *      operationId="markPatientNotificationAsRead",
     *      tags={"Patient"},
     *      summary="Marking notification as read",
     *      description="Marking notifiaction as read",
     *      security={{"passport":{}}},
     *
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */

    public function markNotificationAsRead(Request $request)
    {
        return $this->patientRepository->markNotificationAsRead($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/markAllNotificationsAsRead",
     *      operationId="markPatientAllNotificationsAsRead",
     *      tags={"Patient"},
     *      summary="Marking all notifications as read",
     *      description="Marking all notifiactions as read",
     *      security={{"passport":{}}},
     *
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function markAllNotificationsAsRead(Request $request)
    {
        return $this->patientRepository->markAllNotificationsAsRead($request);
    }

}
