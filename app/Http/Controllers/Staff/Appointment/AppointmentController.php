<?php

namespace App\Http\Controllers\Staff\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Practice\Appointment\CreateRequest;
use App\Models\Doctor\Doctor;
use App\Models\Doctor\Specialization;
use App\Repositories\Staff\Interfaces\Appointment\AppointmentRepositoryInterface;
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
     *      path="/backend/api/staff/create-appointment",
     *      operationId="staffCreateAppointment",
     *      tags={"Practice"},
     *      summary="Staff create appointment",
     *      description="Staff create appointment send notify by email doctor and patient",
     *       security={{"passport":{}}},
     *      @OA\Parameter(
     *          name="doctor_id",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *          @OA\Parameter(
     *          name="patient_id",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *  @OA\Parameter(
     *          name="medical_problem_id[]",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *  @OA\Parameter(
     *          name="doctor_slot_id",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *  @OA\Parameter(
     *          name="date",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *        @OA\Parameter(
     *          name="start_time",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="end_time",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="instructions",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *        @OA\Parameter(
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
     *
     **/
    public function createAppointment(CreateRequest $request)
    {
        return $this->appointmentRepository->createAppointment($request->all());
    }

    /**
     * @OA\Get(
     *      path="/backend/api/staff/appointment-list",
     *      operationId="staffAppointmentList",
     *      tags={"Staff"},
     *      summary="Staff relate appointment list",
     *      description="Staff relate appointment list",
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
        return $this->appointmentRepository->appointmentList($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/staff/re-schedule/{id}",
     *      operationId="staffReScheduleAppointment",
     *      tags={"Staff"},
     *      summary="Staff re-schedule appointment",
     *      description="Staff re-schedule appointment send notify by email doctor and patient ",
     *
     *       security={
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
     *
     *      @OA\Parameter(
     *          name="doctor_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *          @OA\Parameter(
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
     *       @OA\Parameter(
     *          name="instructions",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *        @OA\Parameter(
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
     * @OA\Get(
     *      path="/backend/api/staff/doctor",
     *      operationId="selectedAllDoctorList",
     *      tags={"Staff"},
     *
     *      summary="Selected all doctor list relate to practice",
     *      description="Selected all doctor list relate to practice",
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
    public function practiceDoctor(Request $request)
    {
        return $this->appointmentRepository->practiceDoctor($request);
    }

      /**
     * @OA\Post(
     *      path="/backend/api/staff/doctor-slot/{id}",
     *      operationId="selectedDoctorSlotList",
     *      tags={"Staff"},
     *      summary="Selected doctor slot list",
     *      description="Selected doctor slot list",
     *     security={
     *         {"passport": {}},
     *   },
     *  @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
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
    public function doctorSlot($doctor_id)
    {
        return $this->appointmentRepository->doctorSlot($doctor_id);
    }

    /**
     * @OA\Get(
     *     path="/backend/api/staff/specialization-list",
     *      operationId="specializationlist",
     *      tags={"Staff"},
     *
     *      summary="Specialization list",
     *      description="Specialization list",
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
    public function specializationList(Request $request)
    {
        return $this->appointmentRepository->specializationList($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/staff/specialization-doctor",
     *      operationId="staffSelectedSpecializationShowDoctorList",
     *      tags={"Staff"},
     *
     *      summary="Selected specialization show doctor list",
     *      description="Selected  specialization show doctor list",
     *     security={
     *         {"passport": {}},
     *   },
     *  @OA\Parameter(
     *          name="specialization_id",
     *          in="path",
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
    public function specializationsWithDoctor(Request $request, Doctor $doctorModel, Specialization $specializationModel)
    {
        return $this->appointmentRepository->specializationsWithDoctor($request, $doctorModel,$specializationModel);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/staff/doctor-specializations-list",
     *      operationId="selectedDoctorSpecializationsList",
     *      tags={"Staff"},
     *      summary="Selected doctor specializations list ",
     *      description="Selected doctor specializations list",
     *     security={
     *         {"passport": {}},
     *   },
     *  @OA\Parameter(
     *          name="doctor_id",
     *          in="path",
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
    public function doctorSpecializationsList(Request $request, Specialization $specializationModel)
    {
        return $this->appointmentRepository->doctorSpecializationsList($request,$specializationModel);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/staff/medical-problem-list",
     *      operationId="staffMedicalProblemList",
     *      tags={"Staff"},
     *
     *      summary="Medical problem list",
     *      description="Medical problem list",
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
     *   path="/backend/api/staff/appointment-date",
     *      operationId="appointmentList",
     *      tags={"Staff"},
     *      summary=" Appointment List ",
     *      description="Appointment list also search by id & date ",
     *       security={{"passport":{}}},
     *      @OA\Parameter(
     *          name="doctor_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
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
    public function getAppointmentByIdAndDate(Request $request)
    {
        return $this->appointmentRepository->getAppointmentByIdAndDate($request);
    }
}
