<?php

namespace App\Http\Controllers\Subscription\SubscriptionDiscount;

use App\Http\Controllers\Controller;
use App\Repositories\Subscription\Interfaces\SubscriptionDiscount\SubscriptionDiscountRepositoryInterface;
use App\Http\Requests\Subscription\SubscriptionDiscount\{CreateSubscriptionDiscountRequest,ChangeSubscriptionDiscountStatusRequest};

class SubscriptionDiscountController extends Controller
{

    private SubscriptionDiscountRepositoryInterface $subscriptionDiscountRepository;
    public function __construct(SubscriptionDiscountRepositoryInterface $subscriptionDiscountRepository)
    {
        $this->subscriptionDiscountRepository = $subscriptionDiscountRepository;
    }


    /**
     * @OA\Post(
     *      path="/backend/api/superAdmin/create-subscription-discount/",
     *      operationId="createSubscriptiOnDiscount",
     *      tags={"SuperAdmin"},
     *      security={{"passport":{}}},
     *      summary="create subscription discount",
     *      description="create subscription discount",
     *      @OA\Parameter(
     *      name="subscription_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="plan_discount_percent",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="plan_discount_till",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function createSubscriptionDiscount(CreateSubscriptionDiscountRequest $request)
    {
        return $this->subscriptionDiscountRepository->createSubscriptionDiscount($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/superAdmin/change-subscription-discount-status/",
     *      operationId="changeSubscriptionDiscountStatus",
     *      tags={"SuperAdmin"},
     *      security={{"passport":{}}},
     *      summary="change subscription discount status",
     *      description="change subscription discount status",
     *      @OA\Parameter(
     *      name="subscription_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function changeSubscriptionDiscountStatus(ChangeSubscriptionDiscountStatusRequest $request)
    {
        return $this->subscriptionDiscountRepository->changeSubscriptionDiscountStatus($request);
    }

}
