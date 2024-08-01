<?php

namespace App\Http\Controllers\Insurance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Insurance\Interfaces\Insurance\InsuranceRepositoryInterface;


class InsuranceController extends Controller
{
    private InsuranceRepositoryInterface $insuranceRepository;
    public function __construct(InsuranceRepositoryInterface $insuranceRepository)
    {
        $this->insuranceRepository = $insuranceRepository;
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/add-insurance",
     *      operationId="insuranceBypractice",
     *      tags={"Practice"},
     *
     *      summary="Insurance by practice",
     *      description="Insurance by practice",
     *     security={
     *         {"passport": {}},
     *   },
     *   @OA\Parameter(
     *          name="patient_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  *   @OA\Parameter(
     *          name="insurance_name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *  *   @OA\Parameter(
     *          name="company",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * *  *   @OA\Parameter(
     *          name="insurance_id",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * *  *   @OA\Parameter(
     *          name="amount",
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

    public function addInsurance(Request $request){
        return $this->insuranceRepository->addInsurance($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/practice/insurance-list",
     *      operationId="insuranceList",
     *      tags={"Practice"},
     *
     *      summary="Insurance list",
     *      description="Insurance list",
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
    public function insuranceList($id){
        return $this->insuranceRepository->insuranceList($id);
    }
/**
     * @OA\Get(
     *      path="/backend/api/practice/insurance-company-list/{id}",
     *      operationId="insuranceCompnayName",
     *      tags={"Practice"},
     *
     *      summary="Insurance Compnay Name",
     *      description="Insurance Compnay Name",
     *     security={
     *         {"passport": {}},
     *   },
     *  @OA\Parameter(
     *          name="id",
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

    public function InsuranceCompanyList($id){
        return $this->insuranceRepository->insuranceCompanyList($id);
    }

}


