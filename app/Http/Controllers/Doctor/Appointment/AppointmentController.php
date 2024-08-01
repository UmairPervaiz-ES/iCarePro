<?php

namespace App\Http\Controllers\Doctor\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\Appointment\CreateRequest;
use App\Models\Appointment\Appointment;
use App\Repositories\Doctor\Interfaces\Appointment\AppointmentRepositoryInterface;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    private AppointmentRepositoryInterface $appointmentRepository;
    public function __construct(AppointmentRepositoryInterface $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctors/appointment-list",
     *      operationId="doctorAppointmentList",
     *      tags={"Doctor"},
     *
     *      summary="doctor appointment list",
     *      description="doctor appointment list",
     *     security={
     *         {"passport": {}},
     *   },

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function appointmentList(Request $request)
    {
        return $this->appointmentRepository->appointmentList($request->all());
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctors/doctor-slot",
     *      operationId="doctorSlotList",
     *      tags={"Doctor"},
     *
     *      summary="doctor slot list",
     *      description="doctor slot list",
     *     security={
     *         {"passport": {}},
     *   },

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function doctorSlot()
    {
        return $this->appointmentRepository->doctorSlot();
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctors/create-appointment",
     *      operationId="createAppointment",
     *      tags={"Doctor"},
     *      summary="Doctor create appointment",
     *      description=" Doctor create appointment send notify by email doctor and patient ",
     *
     *   *     security={
     *         {"passport": {}},
     *   },
     *
     *      @OA\Parameter(
     *          name="patient_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *           @OA\Parameter(
     *          name="medical_problem_id[]",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *           @OA\Parameter(
     *          name="doctor_slot_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *        @OA\Parameter(
     *          name="date",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *        @OA\Parameter(
     *          name="start_time",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="end_time",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *       @OA\Parameter(
     *          name="instructions",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *       @OA\Parameter(
     *          name="appointment_type",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
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
    public function createAppointment(CreateRequest $request)
    {
        return $this->appointmentRepository->createAppointment($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctors/re-schedule/{id}",
     *      operationId="reScheduleAppointment",
     *      tags={"Doctor"},
     *      summary="Doctor re-schedule appointment",
     *      description=" Doctor re-schedule appointment  send notify by email doctor and patient",
     *
     *   *     security={
     *         {"passport": {}},
     *   },
     * @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="patient_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *           @OA\Parameter(
     *          name="medical_problem_id[]",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *           @OA\Parameter(
     *          name="doctor_slot_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *        @OA\Parameter(
     *          name="date",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *        @OA\Parameter(
     *          name="start_time",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="end_time",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *       @OA\Parameter(
     *          name="instructions",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *       @OA\Parameter(
     *          name="appointment_type",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
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
    public function reSchedule(CreateRequest $request)
    {
        return $this->appointmentRepository->reSchedule($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctors/patient-list",
     *      operationId="patientList",
     *      tags={"Doctor"},
     *
     *      summary="Patient list",
     *      description="Patient list",
     *     security={
     *         {"passport": {}},
     *   },

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function patientList(Request $request)
    {
        return $this->appointmentRepository->patientList($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/doctors/medical-problem-list",
     *      operationId="medicalProblemList ",
     *      tags={"Doctor"},
     *
     *      summary="Medical problem list ",
     *      description="Medical problem list ",
     *     security={
     *         {"passport": {}},
     *   },

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function medicalProblemList()
    {

        return $this->appointmentRepository->medicalProblemList();
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctors/appointment-date",
     *      operationId="appointmentListSearchByIDAndDate",
     *      tags={"Doctor"},
     *      summary="Appointment List ",
     *      description="Appointment list also search by id & date",
     *       security={{"passport":{}}},
     *

     *
     *  @OA\Parameter(
     *          name="date",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     *
     **/
    public function getAppointmentByIdAndDate(Request $request)
    {
        return $this->appointmentRepository->getAppointmentByIdAndDate($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/doctor/appointments-count",
     *      operationId="appointmentListMonthlyCount",
     *      tags={"Doctor"},
     *      summary="Appointment list monthly count",
     *      description="Appointment list monthly count",
     *       security={{"passport":{}}},
     *
     *
     *  @OA\Parameter(
     *          name="date",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     *
     **/
    public function appointmentListMonthlyCount(Request $request)
    {
        return $this->appointmentRepository->appointmentListMonthlyCount($request);
    }

    /**
     * @OA\get(
     *      path="/backend/api/doctor/appointment-list-monthly",
     *      operationId="appointmentListByGroupMonth",
     *      tags={"Doctor"},
     *
     *      summary="Appointment list by group month",
     *      description="Appointment list by group month",
     *     security={
     *         {"passport": {}},
     *   },
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function appointmentListByMonth()
    {
        return $this->appointmentRepository->appointmentListByMonth();
    }

    /**
     * @OA\get(
     *      path="/backend/api/doctor/appointment-list-previous-monthly",
     *      operationId="appointmentListByGroupPerviousMonth",
     *      tags={"Doctor"},
     *
     *      summary="Appointment list by group pervious month",
     *      description="Appointment list by group pervious month",
     *     security={
     *         {"passport": {}},
     *   },
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function appointmentListByMonthToPreviousDate()
    {
        return $this->appointmentRepository->appointmentListByMonthToPreviousDate();
    }



    /**
     * @OA\Post(
     *      path="/backend/api/doctor/create-zoom-user",
     *      operationId="zoomAccount",
     *      tags={"Doctor"},
     *
     *      summary="Integrating zoom account",
     *      description="Integrating zoom account",
     *     security={
     *         {"passport": {}},
     *   },
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function createZoomUser(Request $request)
    {
        return $this->appointmentRepository->createZoomUser($request);
    }




 /**
     * @OA\get(
     *      path="/backend/api/doctor/patient-appointment-list",
     *      operationId="appointmentListRelatedToPatient",
     *      tags={"Doctor"},
     *
     *      summary="Appointment list relate to patient",
     *      description="All appointment and date wise appointment list those create by  doctor with patient details and also search by appointment id",
     *     security={
     *         {"passport": {}},
     *   },
     *      @OA\Parameter(
     *          name="date",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="patient_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="appointment_key",
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






    public function patientAppointmentListForDoctor(Request $request, Appointment $appointmentModel){

        return $this->appointmentRepository->patientAppointmentListForDoctor($request,$appointmentModel);

    }
}

