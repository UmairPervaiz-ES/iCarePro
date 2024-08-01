<?php

namespace App\Http\Controllers\Practice\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Models\Doctor\Doctor;
use App\Repositories\Practice\Interfaces\Doctor\DoctorRepositoryInterface;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    private DoctorRepositoryInterface $doctorRepository;
    public function __construct(DoctorRepositoryInterface $doctorRepository)
    {
        $this->doctorRepository = $doctorRepository;
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/doctors",
     *      operationId="practiceListOfDoctors",
     *      tags={"Practice"},
     *      security={
     *         {"passport": {}},
     *      },
     *      summary="List of doctors",
     *      description="List of doctors can be filtered by passing different parameters",
     *
     *      @OA\Parameter(
     *          name="specialization",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="first_name",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="phone_number",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="last_name",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="is_active",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="kyc_status",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="doctor_key",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="search",
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

    public function listOfDoctors(Request $request)
    {
        return $this->doctorRepository->listOfDoctors($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/practice/doctor/{id}",
     *      operationId="doctorByID",
     *      tags={"Practice"},
     *      security={
     *         {"passport": {}},
     *      },
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      summary="Retreiving doctor by ID",
     *      description="Retreiving doctor by ID",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */

    public function doctorByID($id)
    {
        return $this->doctorRepository->doctorByID($id);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/practice/doctor-pending-list",
     *      operationId="doctorPendingList",
     *      tags={"Practice"},
     *      security={
     *         {"passport": {}},
     *      },
     *      summary="Doctor pending list",
     *      description="Doctor with pending kyc status list",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */

    public function doctorPendingList()
    {

        return $this->doctorRepository->doctorPendingList();
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/doctor-pending-response/{id}",
     *      operationId="doctorPendingResponse",
     *      tags={"Practice"},
     *      summary="Updating doctor request",
     *  security={
     *         {"passport": {}},
     *      },
     *      description="Updating doctor registration request status and sending reponse to pending doctor via email",
     *
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          @OA\Parameter(
     *          name="kyc_status",
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

    public function doctorPendingListResponse(Request $request, $id)
    {

        return $this->doctorRepository->doctorPendingListResponse($request, $id);
    }

    /**
     * @OA\Get (
     *      path="/backend/api/practice/doctor-specializations",
     *      operationId="doctorSpecializations",
     *      tags={"Practice"},
     *      summary="Doctor specializations",
     *      security={
     *         {"passport": {}},
     *      },
     *      description="Retreiving all doctor specializations present in our portal",
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

    public function doctorSpecializations()
    {
        return $this->doctorRepository->doctorSpecializations();
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/store-doctor",
     *      operationId="addDoctor",
     *      tags={"Practice"},
     *      summary="Adding doctor",
     *      security={
     *         {"passport": {}},
     *      },
     *      description=" Adding doctor details",
     *
     *      @OA\Parameter(
     *          name="doctor_id",
     *          in="path",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="suffix",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="first_name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="middle_name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="last_name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="primary_email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="secondary_email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="gender",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="dob",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="country_code_primary_phone_number",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="primary_phone_number",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="marital_status",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="current_country_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="current_state_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="current_city_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="home_town_country_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="home_town_state_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="home_town_city_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="current_address_1",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="current_address_2",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="current_zip_code",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="home_town_address_1",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="home_town_address_2",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="home_town_zip_code",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="licence_number",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="emirate_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="passport_number",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="specialization_id[0]",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="specialization_id[1]",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="profile_photo_url",
     *                      type="array",
     *                      @OA\Items(
     *                          type="file",
     *                          format="binary",
     *                      ),
     *                  )
     *              )
     *          )
     *      ),
     *
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="licence_photo_url",
     *                      type="array",
     *                      @OA\Items(
     *                          type="file",
     *                          format="binary",
     *                      ),
     *                  )
     *              )
     *          )
     *      ),
     *
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="passport_photo_url",
     *                      type="array",
     *                      @OA\Items(
     *                          type="file",
     *                          format="binary",
     *                      ),
     *                  )
     *              )
     *          )
     *      ),
     *
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="emirate_photo_url",
     *                      type="array",
     *                      @OA\Items(
     *                          type="file",
     *                          format="binary",
     *                      ),
     *                  )
     *              )
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

    public function store(StoreDoctorRequest $request)
    {
        return $this->doctorRepository->store($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/send-doctor-registration-link",
     *      operationId="sendDoctorRegistrationLink",
     *      tags={"Practice"},
     *      summary="Sending doctor registration link",
     *      security={
     *         {"passport": {}},
     *      },
     *      description="Sending doctor registration link by entering his initial values",
     *
     *      @OA\Parameter(
     *          name="first_name",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="middle_name",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="last_name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="primary_email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="country_code_primary_phone_number",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="primary_phone_number",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="gender",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="dob",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="send_invite",
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

    public function sendRegistrationLinkToDoctor(Request $request)
    {
        return $this->doctorRepository->sendRegistrationLinkToDoctor($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/practice/list-of-registration-requests",
     *      operationId="listOfRegistrationRequests",
     *      tags={"Practice"},
     *      summary="List of registration requests.",
     *      security={
     *         {"passport": {}},
     *      },
     *      description="List of registration requests sent by practice to doctors.",
     *
     *      @OA\Parameter(
     *          name="pagination",
     *          in="path",
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

    public function listOfDoctorRequestsSent($pagination)
    {
        return $this->doctorRepository->listOfDoctorRequestsSent($pagination);
    }

    /**
     *  Description: Updating KYC status for respective doctor from incoming KYC response from ShuftiPro
     *  1) This method receives API response given by shuftiPro
     *  2) Doctor KYC status is updated by comparing kyc_reference_no saved for doctor by incoming kyc_reference_no
     *
     * @param Request $request
     * @return void
     */
    public function kyc_response(Request $request)
    {
        // Saving entire kyc_response for respective doctor
        Doctor::where('primary_email', $request['email'])->update(['kyc_response' => $request->all()]);

        // Updating kyc_status for respective doctor
        $doctor =  Doctor::where('kyc_reference_no', $request['reference'])->first();

        // Updating kyc status for doctor
        if ($request['event'] == 'request.pending'){
            $doctor->update(['kyc_status' => 'Pending']);
        }
        if ($request['event'] == 'request.invalid')
        {
            $doctor->update(['kyc_status' => 'Invalid']);
        }
        if ($request['event'] == 'verification.cancelled')
        {
            $doctor->update(['kyc_status' => 'Canceled']);
        }
        if ($request['event'] == 'request.timeout')
        {
            $doctor->update(['kyc_status' => 'Timeout']);
        }
        if ($request['event'] == 'request.unauthorized')
        {
            $doctor->update(['kyc_status' => 'Unauthorized']);
        }
        if ($request['event'] == 'verification.accepted')
        {
            $doctor->update(['kyc_status' => 'Accepted']);
        }
        if ($request['event'] == 'verification.declined')
        {
            $doctor->update(['kyc_status' => 'Declined']);
        }
        if ($request['event'] == 'verification.status.changed')
        {
            $doctor->update(['kyc_status' => 'Verification status is changed.']);
        }
        if ($request['event'] == 'request.deleted')
        {
            $doctor->update(['kyc_status' => 'Request deleted']);
        }
        if ($request['event'] == 'request.received')
        {
            $doctor->update(['kyc_status' => 'Request received']);
        }
        if ($request['event'] == 'review.pending')
        {
            $doctor->update(['kyc_status' => 'Review pending']);
        }

        // Updating decline codes if present in the response.
        if (count($request['declined_codes']) > 0)
        {
            $doctor->update(['kyc_declined_status' => implode(',', $request['declined_codes'])]);
        }

    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/doctor-appointment-list",
     *      operationId="doctorAppointmentListByFilterDate",
     *      tags={"Practice"},
     *      summary="doctor appointment list by filter date",
     *      security={
     *         {"passport": {}},
     *      },
     *      description="doctor appointment list by filter date",
     *
     *      @OA\Parameter(
     *          name="doctor_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
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

    public function doctorAppointmentList(Request $request)
    {
        return $this->doctorRepository->doctorAppointmentList($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/doctor-dated-appointments",
     *      operationId="doctorsDatedAppointmentsListForCalendarView",
     *      tags={"Practice"},
     *      summary="doctors dated appointments list for calendar view",
     *      security={
     *         {"passport": {}},
     *      },
     *      description="Doctors dated appointments list for calendar view",
     *
     *      @OA\Parameter(
     *          name="doctor_id",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *      @OA\Parameter(
     *          name="start_date",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *      @OA\Parameter(
     *          name="end_date",
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

    public function calendarAppointmentsViewDates(Request $request)
    {
        return $this->doctorRepository->calendarAppointmentsViewDates($request);
    }

}
