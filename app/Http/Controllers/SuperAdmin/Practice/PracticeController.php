<?php

namespace App\Http\Controllers\SuperAdmin\Practice;

use App\Http\Controllers\Controller;
use App\Repositories\SuperAdmin\Interfaces\Practice\PracticeRepositoryInterface;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    private PracticeRepositoryInterface $practiceRepository;
    public function __construct(PracticeRepositoryInterface $practiceRepository)
    {
        $this->practiceRepository = $practiceRepository;
    }

    /**
     * @OA\Post(
     *      path="/backend/api/superAdmin/practices",
     *      operationId="practices_list",
     *      tags={"SuperAdmin"},
     *      security={
     *         {"passport": {}},
     *      },
     *      summary="Showing registered practices list",
     *      description="Showing registered practices list",
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
    public function practices(Request $request)
    {
        return $this->practiceRepository->practices($request);
    }

    /**
     * @OA\Get (
     *      path="/backend/api/superAdmin/practice-details",
     *      operationId="practice_detials",
     *      tags={"SuperAdmin"},
     *      security={
     *         {"passport": {}},
     *      },
     *      summary="Retrieving registered practice list",
     *      description=""Retrieving registered practice list"",
     *
     *      @OA\Parameter(
     *      name="practice_id",
     *      in="path",
     *      required=true,
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
    public function practiceDetails($id)
    {
        return $this->practiceRepository->practiceDetails($id);
    }
}
