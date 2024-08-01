<?php

namespace App\Http\Controllers\Subscription\Subscription;

use App\Http\Controllers\Controller;
use App\Repositories\Subscription\Interfaces\Subscription\SubscriptionRepositoryInterface;
use App\Http\Requests\Subscription\Subscription\{CreateSubscriptionRequest,ChangeSubscriptionStatusRequest,EditSubscriptionRequest};

class SubscriptionController extends Controller
{
    private SubscriptionRepositoryInterface $subscriptionRepository;
    public function __construct(SubscriptionRepositoryInterface $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }
     /**
     * @OA\Post(
     *      path="/backend/api/superAdmin/create-subscription",
     *      operationId="createSubscription",
     *      tags={"SuperAdmin"},
     *      summary="Create New Subscription Plan",
     *      description="Create New Subscription Plan",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="description",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *      name="duration_days",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="price",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="allowed_doctors",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="allowed_patients",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="allowed_appointments",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="allowed_staff",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="is_trail",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="permissions[0]",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *       )
     *      ),
     *    @OA\Parameter(
     *      name="permissions[1]",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *       )
     *      ),
     *    @OA\Parameter(
     *      name="permissions[2]",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *       )
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
    public function createSubscription(CreateSubscriptionRequest $request)
    {
        return $this->subscriptionRepository->createSubscription($request->all());
    }


    /**
     * @OA\Get(
     *      path="/backend/api/superAdmin/get-subscriptions/{Per_Page_Limit}",
     *      operationId="getAllSubscription",
     *      tags={"SuperAdmin"},
     *      summary="List All Subscription Plans",
     *      description="List All Subscription Plans",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *      name="Per_Page_Limit",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function getSubscriptions($Limit_Per_Page){
        return $this->subscriptionRepository->getSubscriptions($Limit_Per_Page);
    }

     /**
     * @OA\Post(
     *      path="/backend/api/superAdmin/change-subscription-status/",
     *      operationId="changeSubscriptionStatus",
     *      tags={"SuperAdmin"},
     *      security={{"passport":{}}},
     *      summary="Change Subscription Plan Status publish/unpublish",
     *      description="Change Subscription Plan Status publish/unpublish",
     *      @OA\Parameter(
     *      name="subscription_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function changeSubscriptionStatus(ChangeSubscriptionStatusRequest $request){
        return $this->subscriptionRepository->changeSubscriptionStatus($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/superAdmin/show-all-permissions/",
     *      operationId="showAllPermissions",
     *      tags={"SuperAdmin"},
     *      security={{"passport":{}}},
     *      summary="Show All Permissions",
     *      description="Show All Permissions to be checked for the subscription",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function showAllPermissions(){
        return $this->subscriptionRepository->showAllPermissions();
    }

    /**
     * @OA\Get(
     *      path="/backend/api/superAdmin/view-subscription/{id}",
     *      operationId="viewSubscription",
     *      tags={"SuperAdmin"},
     *      security={{"passport":{}}},
     *      summary="view subscription",
     *      description="view subscription by id",
     *      @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function viewSubscription($id){
        return $this->subscriptionRepository->viewSubscription($id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/superAdmin/edit-subscription/",
     *      operationId="editSubscription",
     *      tags={"SuperAdmin"},
     *      security={{"passport":{}}},
     *      summary="edit subscription",
     *      description="edit subscription by id",
     *      @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="description",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *      name="duration_days",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="price",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="status",
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
    public function editSubscription(EditSubscriptionRequest $request){
        return $this->subscriptionRepository->editSubscription($request);
    }
}
