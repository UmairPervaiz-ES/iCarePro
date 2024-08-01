<?php

namespace App\Http\Controllers\SuperAdmin\PracticeRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Practice\PracticeRegister\PracticeRequest;
use App\Repositories\SuperAdmin\Interfaces\PracticeRequest\PracticeRequestRepositoryInterface;
use Illuminate\Http\Request;


class PracticeRequestController extends Controller
{

    private PracticeRequestRepositoryInterface $practiceRepository;
    public function __construct(PracticeRequestRepositoryInterface $practiceRepository)
    {
        $this->practiceRepository = $practiceRepository;
    }

    /**
     * @OA\Post(
     *      path="/backend/api/superAdmin/initial-practice-request",
     *      operationId="initial_practice_request",
     *      tags={"SuperAdmin"},
     *      security={
     *         {"passport": {}},
     *      },
     *      summary="Show Pending Initial Requests",
     *      description="Show Pending Initial Requests",
     *
     *      @OA\Parameter(
     *      name="pagination",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function initialPracticeRequest(Request $request)
    {
        return $this->practiceRepository->initialPracticeRequest($request);

    }

    /**
     * @OA\Post(
     *      path="/backend/api/superAdmin/initial-practice-response",
     *      operationId="initialPracticeResponse",
     *      tags={"SuperAdmin"},
     *      summary="Create initial practice response",
     *  security={
     *         {"passport": {}},
     *      },
     *      description=" initial practice request response to super admin",
     *
     *      @OA\Parameter(
     *          name="id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          @OA\Parameter(
     *          name="status",
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
    public function initialPracticeRequestResponse(Request $request)
    {
        return $this->practiceRepository->initialPracticeRequestResponse($request);

    }

    /**
     * @OA\Post(
     *      path="/backend/api/superAdmin/practice-request",
     *      operationId="get practice_request",
     *      tags={"SuperAdmin"},
     *      security={
     *         {"passport": {}},
     *      },
     *      summary="Get Practice Requests",
     *      description="Get Requests",
     *
     *      @OA\Parameter(
     *      name="records",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *
     *      @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
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
    public function practiceRequestGet(Request $request)
    {
        return $this->practiceRepository->practiceRequestGet($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/superAdmin/practice-request-response",
     *      operationId="practiceResponse",
     *      tags={"SuperAdmin"},
     *      summary=" practice response",
     *  security={
     *         {"passport": {}},
     *      },
     *      description="practice request response to super admin",
     *
     *      @OA\Parameter(
     *          name="id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          @OA\Parameter(
     *          name="status",
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
    public function practiceRequestResponse(Request $request)
    {
        return $this->practiceRepository->practiceRequestResponse($request);
    }
}
