<?php

namespace App\Http\Controllers\Patient\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\Appointment\CreateRequest;
use App\Models\Appointment\Appointment;
use App\Models\Doctor\Doctor;
use App\Models\Doctor\DoctorPractice;
use App\Models\Doctor\Specialization;
use App\Repositories\Patient\Interfaces\Appointment\AppointmentRepositoryInterface;
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
     *      path="/backend/api/patient/appointment-list",
     *      operationId="patientAppointmentsList",
     *      tags={"Patient"},
     *
     *      summary="Patient appointment list",
     *      description="Patient appointment list also search by appointment date",
     *     security={
     *         {"passport": {}},
     *   },


     *      @OA\Parameter(
     *          name="date",
     *          in="query",
     *          required=false,
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


    public function appointmentList(Request $request)
    {

        return $this->appointmentRepository->appointmentList($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/re-schedule/{id}",
     *      operationId="patientRescheduleAppointment",
     *      tags={"Patient"},
     *      summary="Patient re-schedule appointment",
     *      description="Patient re-schedule appointment send notify by email doctor and patient ",
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
     *
     *      @OA\Parameter(
     *          name="practice_id",
     *          in="query",
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
     *
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
     *
     *
     *        @OA\Parameter(
     *          name="date",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
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
     *
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
     *      path="/backend/api/patient/practice-list",
     *      operationId="practiceList",
     *      tags={"Patient"},
     *
     *      summary="Practice list",
     *      description="Practice list",
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

    public function practiceList()
    {

        return $this->appointmentRepository->practiceList();
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/doctor-list/{id}",
     *      operationId="selectedPracticeAllDoctorList",
     *      tags={"Patient"},
     *
     *      summary="Doctor list",
     *      description="selected practice all doctor list",
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

    public function doctorList(Request $request)
    {

        return $this->appointmentRepository->doctorList($request);
    }


    /**
     * @OA\Post(
     *      path="/backend/api/patient/appointment-create",
     *      operationId="patientCreateAppointment",
     *      tags={"Patient"},
     *      summary="Patient create appointment",
     *      description="Patient create appointment send notify by email doctor and patient ",
     *
     *   *     security={
     *         {"passport": {}},
     *   },
     *
     *   @OA\Parameter(
     *          name="practice_id",
     *          in="query",
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
     *
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
     *
     *
     *        @OA\Parameter(
     *          name="date",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
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
     *       @OA\Parameter(
     *          name="appointment_type",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
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
     */


    public function appointmentCreate(CreateRequest $request)
    {

        return $this->appointmentRepository->appointmentCreate($request);
    }
    /**
     * @OA\Post(
     *      path="/backend/api/patient/doctor-specializations-list/{id}",
     *      operationId="selectedDoctorSpecializationsList ",
     *      tags={"Patient"},
     *
     *      summary="selected doctor specializations list",
     *      description="selected doctor specializations list",
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



    public function doctorSpecializationsList(Request $request, Doctor $doctorModel, Specialization $specializationModel)
    {

        return $this->appointmentRepository->doctorSpecializationsList($request, $doctorModel, $specializationModel);
    }

    /**
     * @OA\Get(
     *     path="/backend/api/patient/specialization-list",
     *      operationId="specializationList",
     *      tags={"Patient"},
     *
     *      summary="Specialization list ",
     *      description="Specialization list ",
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

     *      path="/backend/api/patient/specialization-doctor/{id}",
     *      operationId="selectedSpecializationShowDoctorList",
     *      tags={"Patient"},
     *
     *      summary="selected specialization show doctor list ",
     *      description="selected specialization show doctor list those relate to specialization",
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

    public function specializationsWithDoctor(Request $request, DoctorPractice $doctorPracticeModel, Specialization $specializationModel)
    {

        return $this->appointmentRepository->specializationsWithDoctor($request, $doctorPracticeModel, $specializationModel);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/patient/doctor-slot/{id}",
     *      operationId="slotsList",
     *      tags={"Patient"},
     *
     *      summary="Doctor slot list",
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



    public function doctorSlot($id, Request $request)
    {

        return $this->appointmentRepository->doctorSlot($id, $request);
    }



    /**
     * @OA\Get(
     *      path="/backend/api/patient/medical-problem-list",
     *      operationId="appointmentMedicalProblem",
     *      tags={"Patient"},
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
     *      path="/backend/api/patient/appointment-date",
     *      operationId="getAppointmentByIDAndDateByPatient",
     *      tags={"Practice"},
     *      summary="Appointment list by id & date",
     *      description="Patient appointment also search by id & date",
     *       security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *          name="doctor_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
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


    public function getAppointmentByIdAndDate(Request $request ,Appointment $appointmentModel)
    {
        return $this->appointmentRepository->getAppointmentByIdAndDate($request , $appointmentModel);
    }

    public function getAppointmentList(Request $request)
    {
        return $this->appointmentRepository->getAppointmentList($request);
    }

    /**
     * @OA\get(
     *      path="/backend/api/patient/appointment-list-monthly",
     *      operationId="appointmentListForPatientByGroupMonth",
     *      tags={"Patient"},
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
     *      path="/backend/api/patient/appointment-list-pervious-monthly",
     *      operationId="appointmentListForPatientByGroupPerviousMonth",
     *      tags={"Patient"},
     *
     *      summary="appointment list by group pervious month",
     *      description="appointment list by group pervious month",
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

}
