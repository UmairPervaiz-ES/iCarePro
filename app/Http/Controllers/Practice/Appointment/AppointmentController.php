<?php

namespace App\Http\Controllers\Practice\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Practice\Appointment\CreateRequest;
use App\Models\Doctor\Doctor;
use App\Models\Doctor\DoctorPractice;
use App\Models\Doctor\Specialization;
use App\Repositories\Practice\Interfaces\Appointment\AppointmentRepositoryInterface;
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
     *      path="/backend/api/practice/create-appointment",
     *      operationId="practiceCreateAppointment",
     *      tags={"Practice"},
     *      summary="Practice create appointment",
     *      description="Practice create appointment send notify by email doctor and patient",
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
     *
     **/

    public function createAppointment(CreateRequest $request)
    {
        return $this->appointmentRepository->createAppointment($request->all());
    }

    /**
     * @OA\Get(
     *      path="/backend/api/practice/appointment-list",
     *      operationId="practiceAppointmentList",
     *      tags={"Practice"},
     *
     *      summary="Practice appointment list",
     *      description="practice appointment list",
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
     *      path="/backend/api/practice/re-schedule/{id}",
     *      operationId="practiceReScheduleAppointment",
     *      tags={"Practice"},
     *      summary="Practice re-schedule appointment",
     *      description=" Practice re-schedule appointment send notify by email doctor and patient ",
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
     *      path="/backend/api/practice/doctor",
     *      operationId="doctorList",
     *      tags={"Practice"},
     *
     *      summary="Doctor list",
     *      description="Doctor list show to practice those register this practice also doctor search by first name ,middle name and last name",
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
     *      path="/backend/api/practice/doctor-slot/{id}",
     *      operationId="selectedDoctorSlotList ",
     *      tags={"Practice"},
     *      summary="Selected doctor slot list ",
     *      description="Practice selecte doctor show all slot list",
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
     *     path="/backend/api/practice/specialization-list",
     *      operationId="specializationsList ",
     *      tags={"Practice"},
     *
     *      summary="specialization list ",
     *      description="specialization list ",
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
     *      path="/backend/api/practice/specialization-doctor",
     *      operationId="selectedSpecializationShowSoctorList ",
     *      tags={"Practice"},
     *
     *      summary="Selected  specialization show doctor list ",
     *      description="Selected  specialization show doctor list",
     *     security={
     *         {"passport": {}},
     *   },
     *  @OA\Parameter(
     *          name="specialization_id",
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
        return $this->appointmentRepository->specializationsWithDoctor($request, $doctorPracticeModel,$specializationModel);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/doctor-specializations-list",
     *      operationId="practiceSelectedDoctorSpecializationsList",
     *      tags={"Practice"},
     *      summary="selected doctor specializations list ",
     *      description="Selected doctor specializations list",
     *     security={
     *         {"passport": {}},
     *   },
     *  @OA\Parameter(
     *          name="doctor_id",
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


    public function doctorSpecializationsList(Request $request, Specialization $specializationModel)
    {
        return $this->appointmentRepository->doctorSpecializationsList($request,$specializationModel);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/practice/medical-problem-list",
     *      operationId="medicalProblemList",
     *      tags={"Practice"},
     *
     *      summary="Medical problem",
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
     *      path="/backend/api/practice/appointment-date",
     *      operationId="listAppointmentByIDAndDate",
     *      tags={"Practice"},
     *      summary="Appointment",
     *      description="List appointment by id & date",
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


     /**
     * @OA\Get(
     *      path="/backend/api/practice/stats",
     *      operationId="practiceDashboardStats",
     *      tags={"Practice"},
     *
     *      summary="Practice Dashboard Stats",
     *      description="Practice Dashboard Stats",
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

    public function practiceStats()
    {
        return $this->appointmentRepository->practiceStats();
    }

        /**
     * @OA\Get(
     *      path="/backend/api/practice/appointment-chart",
     *      operationId="practiceDashboardAppointmentPiChart",
     *      tags={"Practice"},
     *
     *      summary="Practice Dashboard Appointment Pi Chart",
     *      description="Practice Dashboard Appointment Pi Chart return count of total and upcoming appointment total practice patients and doctors",
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


    public function practiceAppointmentPiChart()
    {
        return $this->appointmentRepository->practiceAppointmentPiChart();
    }


        /**
     * @OA\Get(
     *      path="/backend/api/practice/appointment-spline-graph",
     *      operationId="practiceDashboardAppointmentSplineGraph",
     *      tags={"Practice"},
     *
     *      summary="Practice Dashboard Appointment Spline Graph",
     *      description="Practice Dashboard Appointment Spline Graph they return count of one week day by day appointment count weekend start from current week",
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

    public function appointmentSplineGraph()
    {
        return $this->appointmentRepository->appointmentSplineGraph();
    }


       /**
     * @OA\Post(
     *      path="/backend/api/practice/appointment-list-monthly-count",
     *      operationId="appointmentListForPracticeMonthlyCount",
     *      tags={"Practice"},
     *      summary="Pppointment list monthly count",
     *      description="Pppointment list monthly count",
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
     * @OA\Post(
     *      path="/backend/api/practice/upcoming-appointments-list",
     *      operationId="upcomingAppointmentList",
     *      tags={"Practice"},
     *      summary="Upcoming appointment list",
     *      description="Upcoming appointment list",
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


    public function upcomingAppointmentList(Request $request)
    {
        return $this->appointmentRepository->upcomingAppointmentList($request);
    }


}
