<?php

namespace App\Http\Controllers\Practice\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Practice\Staff\StoreStaff;
use App\Repositories\Practice\Interfaces\Staff\StaffRepositoryInterface;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    private StaffRepositoryInterface $staffRepository;

    public function __construct(StaffRepositoryInterface $staffRepository)
    {
        $this->staffRepository = $staffRepository;
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/staff",
     *      operationId="staffByID",
     *      tags={"Practice"},
     *      summary="Staff details by id",
     *      description="Staff details by id",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
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

    public function viewDetailsByStaffID(Request $request)
    {
        return $this->staffRepository->viewDetailsByStaffID($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/staffs",
     *      operationId="listOfStaffs",
     *      tags={"Practice"},
     *      summary="List of staffs",
     *      description="List of staffs",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="pagination",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="role",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *     @OA\Parameter(
     *      name="department_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *     @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *     @OA\Parameter(
     *      name="department_type_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *     @OA\Parameter(
     *      name="search",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *     @OA\Parameter(
     *      name="middle_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *     @OA\Parameter(
     *      name="last_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
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

    public function listOfStaff(Request $request)
    {
        return $this->staffRepository->listOfStaff($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/add-staff",
     *      operationId="addStaff",
     *      tags={"Practice"},
     *      summary="Adding practice staff",
     *      description="Adding practice staff",
     *      security={{"passport":{}}},
     *
     *     @OA\Parameter(
     *      name="role_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="department_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="department_employee_type_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="middle_name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="last_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="secondary_email",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="country_code_phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="interger"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="country_code_secondary_phone_number",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="secondary_phone_number",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="gender",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="dob",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *      @OA\RequestBody(
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
     *     @OA\Parameter(
     *      name="home_address_1",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="home_address_2",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="home_town_country_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="home_town_state_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="home_town_city_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="current_zip_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="current_address_1",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="current_address_2",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="current_country_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="current_state_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="current_city_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *     @OA\Parameter(
     *      name="home_zip_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
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

    public function store(StoreStaff $request)
    {
        return $this->staffRepository->store($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/send-credentials-mail-to-staff",
     *      operationId="sendStaffCredentials",
     *      tags={"Practice"},
     *      summary="Sending Staff their account credentials",
     *      description="Sending Staff their account credentials",
     *      security={{"passport":{}}},
     *
     *     @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
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

    public function emailWithCredentials(Request $request)
    {
        return $this->staffRepository->emailWithCredentials($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/staff-status-update",
     *      operationId="staffStatusUpdate",
     *      tags={"Practice"},
     *      summary="Staff status update",
     *      description="Staff status update",
     *      security={{"passport":{}}},
     *
     *      @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
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

    public function statusUpdate(Request $request)
    {
        return $this->staffRepository->statusUpdate($request);
    }
}
