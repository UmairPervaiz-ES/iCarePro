<?php

namespace App\Http\Controllers\Practice\Initial;

use App\Http\Controllers\Controller;
use App\Http\Requests\Practice\Initial\InitialRequest;
use App\Http\Requests\Practice\PracticeRegister\PracticeRequest;
use App\Http\Requests\Practice\PracticeRegister\PracticeRequestDocument;
use App\Repositories\Practice\Interfaces\Initial\InitialRepositoryInterface;
use Illuminate\Http\Request;

class InitialPracticeController extends Controller
{
    private InitialRepositoryInterface $initialRepository;
    public function __construct(InitialRepositoryInterface $initialRepository)
    {
        $this->initialRepository = $initialRepository;
    }

    /**
     * @OA\Post(
     *      path="/backend/api/initial-practice",
     *      operationId="practiceRegistrationRequest",
     *      tags={"Practice"},
     *      summary="Practice registration request",
     *      description="Practice registration request send to super admin . When request is send then send mail to user and super admin . request accepte/reject by super admin notify to user with email after accept request send other link by email user fill the  detail form. ",
     *      @OA\Parameter(
     *          name="practice_name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="country_code",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="phone_number",
     *          in="query",
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
     *      @OA\Parameter(
     *          name="middle_name",
     *          in="query",
     *          required=false,
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
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *    @OA\Parameter(
     *          name="designation",
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
    public function initialPractice(InitialRequest $request)
    {
        return $this->initialRepository->initialPractice($request->validated());
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice-request/{id}",
     *      operationId="practiceRequest",
     *      tags={"Practice" },
     *      summary="Practice Details add ",
     *      description="Practice registration details request send to super admin . When request is send then send mail to user and super admin . request accepte/reject by super admin notify to user with email after accept request send One time password by email",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * @OA\Parameter(
     *          name="country_code",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="phone_number",
     *          in="query",
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
     *      @OA\Parameter(
     *          name="middle_name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="last_name",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="designation",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *    @OA\Parameter(
     *          name="logo_url",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  @OA\Parameter(
     *          name="tax_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  @OA\Parameter(
     *          name="practice_npi",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  @OA\Parameter(
     *          name="practice_taxonomy",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  @OA\Parameter(
     *          name="facility_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  @OA\Parameter(
     *          name="oid",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  @OA\Parameter(
     *          name="clia_number",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  *  @OA\Parameter(
     *          name="privacy_policy",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *  *  @OA\Parameter(
     *          name="address_line_1",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="address_line_2",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="country_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="city_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="state_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="zip_code",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="lat",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="lng",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     * *  *  @OA\Parameter(
     *          name="billing_address_line_1",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="billing_address_line_2",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="billing_country_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="billing_city_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="billing_state_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="billing_zip_code",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="billing_lat",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     * *  @OA\Parameter(
     *          name="billing_lng",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="number"
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
    public function practiceRequest(PracticeRequest $request, $id)
    {
        return $this->initialRepository->practiceRequest($request, $id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice-document/{id}",
     *      operationId="practiceDocument",
     *      tags={"Practice"},
     *      summary="Practice add form",
     *      description="Practice add multi form then send details request",
     *       @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  @OA\Parameter(
     *          name="name",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  @OA\Parameter(
     *          name="type",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="file_path",
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
     *          description="Success",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function practiceDocument(PracticeRequestDocument $request, $id)
    {
        return $this->initialRepository->practiceDocument($request, $id);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/practice-document-delete/{id}",
     *      operationId="practiceDocumentDelete",
     *      tags={"Practice"},
     *      summary="Practice details document delete",
     *      description="Practice details document delete",
     *       @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
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
    public function practiceDocumentDelete($id)
    {
        return $this->initialRepository->practiceDocumentDelete($id);
    }

    /**
     * @OA\Post (
     *      path="/backend/api/practice/notifications",
     *      operationId="practiceNotifications",
     *      tags={"Practice"},
     *      summary="Retreving all practice notifications",
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
        return $this->initialRepository->allNotifications($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/markNotificationAsRead",
     *      operationId="markPracticeNotificationAsRead",
     *      tags={"Practice"},
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
        return $this->initialRepository->markNotificationAsRead($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/markAllNotificationsAsRead",
     *      operationId="markPracticeAllNotificationsAsRead",
     *      tags={"Practice"},
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
        return $this->initialRepository->markAllNotificationsAsRead($request);
    }


      /**
     * @OA\Post(
     *      path="/backend/api//contact-person-email-check",
     *      operationId="practicePersonContactEmailCheck",
     *      tags={"Practice" },
     *      summary="Person email check",
     *      description="person add email check email is already exist or not and if person email exit then show erorr message othervise go to next step",

     * @OA\Parameter(
     *          name="country_code",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="phone_number",
     *          in="query",
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
     *      @OA\Parameter(
     *          name="middle_name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="last_name",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="designation",
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

    public function contactPersonEmailCheck(Request $request)
    {
        return $this->initialRepository->contactPersonEmailCheck($request);
    }
}
